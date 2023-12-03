<?php
namespace App\Service;

use App\Entity\Categoria;
use App\Entity\MedioPersonaEtiqueta;
use App\Entity\Etiqueta;
use App\Entity\Medio;
use App\Entity\Persona;
use Goutte\Client;

class Sensacine implements PaginaInterface
{
    private $meses = array(
        'enero' => 'January',
        'febrero' => 'February',
        'marzo' => 'March',
        'abril' => 'April',
        'mayo' => 'May',
        'junio' => 'June',
        'julio' => 'July',
        'agosto' => 'August',
        'septiembre' => 'September',
        'octubre' => 'October',
        'noviembre' => 'November',
        'diciembre' => 'December'
    );
    private $client;
    private $crawler;
    private $pagina;
    public function __construct(private $categoriaRepository, private $medioRepository, private $personaRepository, private $etiquetaRepository, private $resenaRepository, private $medioPersonaEtiquetaRepository, private $paginaRepository, private $entityManager)
    {
        $this->client = new Client();
        $this->pagina = $this->paginaRepository->findOneBy(["nombre" => "sensacine"]);
    }
    public function obtenerDatos($numero_pagina_personas, $numero_pagina_peliculas, $io)
    {
        $this->crawler = $this->client->request('GET', $this->pagina->getDominio() . '/actores/todo/');
        $limite_pagina = $this->crawler->filter('div.pagination-item-holder')->children()->last()->text();
        for ($i = $numero_pagina_personas; $i <= $limite_pagina; $i++) {
            $this->crawler = $this->client->request('GET', $this->pagina->getDominio() . '/actores/todo/?page=' . $i);
            $this->crawler->filter('#content-layout > section.section.section-wrap.gd-3-cols.gd-gap-20.row-col-sticky > div.gd-col-middle > ul > li > div > div.meta > h2 > a')->each(function ($node) {
                $link = $node->link();
                $this->crawler = $this->client->click($link);
                $persona = $this->crearPersona();
                $this->entityManager->persist($persona);
                $this->entityManager->flush();
            });
            $io->writeln("Numero de pagina para PERSONAS: $i");
        }
        $this->crawler = $this->client->request('GET', $this->pagina->getDominio() . '/peliculas/todas-peliculas/');
        $limite_pagina = $this->crawler->filter('div.pagination-item-holder')->children()->last()->text();
        for ($i = $numero_pagina_peliculas; $i <= $limite_pagina; $i++) {
            $this->crawler = $this->client->request('GET', $this->pagina->getDominio() . '/peliculas/todas-peliculas/?page=' . $i);
            $this->crawler->filter('#content-layout > section.section.section-wrap.gd-3-cols.gd-gap-20.row-col-sticky > div.gd-col-middle > ul > li > div > div.meta > h2 > a')->each(function ($node) {
                $link = $node->link();
                $this->crawler = $this->client->click($link);

                //$titulo = trim(preg_replace('/\b' . preg_quote('Título original', '/') . '\b/', '', $this->crawler->filterXPath('//*[@id="content-layout"]/section/div/div[2]/div[1]/div/div[5]')->text()));
                $titulo = $this->crawler->filter('#content-layout > section > div > div.card.entity-card > div.meta > div > div:nth-child(5)');
                if ($titulo->count() === 0) {
                    $titulo = $this->crawler->filter('div.titlebar-title')->text();
                } else {
                    $titulo = trim(preg_replace('/\b' . preg_quote('Título original', '/') . '\b/', '', $titulo->text()));
                }

                $pelicula = $this->medioRepository->findOneBy(['nombre' => $titulo]);
                if (empty($pelicula)) {
                    $pelicula = new Medio();
                    $pelicula->setNombre($titulo);
                }
                $infoHeader = $this->crawler->filter('#content-layout > section > div > div.card.entity-card > div.meta > div > div.meta-body-item.meta-body-info');
                $arrayHeader = explode('/', $infoHeader->text());
                $reemplazos = ['en cines', 'en Amazon Prime Video'];
                $fechaLanzamiento = trim(str_replace($reemplazos, '', $arrayHeader[0]));
                $fechaLanzamiento = str_replace(array_keys($this->meses), array_values($this->meses), $fechaLanzamiento);
                $fechaLanzamiento = \DateTime::createFromFormat('j \d\e F \d\e Y', $fechaLanzamiento);
                if ($fechaLanzamiento === false)
                    throw new \Exception("Error al analizar la fecha");
                $pelicula->setFechaLanzamiento($fechaLanzamiento);
                // Utiliza expresiones regulares para extraer horas y minutos
                if (preg_match('/(\d+)h\s*(\d+)min/', trim($arrayHeader[1]), $matches)) {
                    $horas = intval($matches[1]);
                    $minutos = intval($matches[2]);

                    // Convierte horas a minutos y suma los minutos
                    $totalMinutos = $horas * 60 + $minutos;

                    $pelicula->setDuracion((int) $totalMinutos);

                    $categorias = explode(',', $arrayHeader[2]);
                    foreach ($categorias as $categoria) {
                        $entity_categoria = $this->crearCategoria(trim($categoria));
                        $pelicula->addCategoria($entity_categoria);
                        $this->pagina->addCategoria($entity_categoria);
                    }
                } else {
                    $categorias = explode(',', $arrayHeader[1]);
                    foreach ($categorias as $categoria) {
                        $entity_categoria = $this->crearCategoria(trim($categoria));
                        $pelicula->addCategoria($entity_categoria);
                        $this->pagina->addCategoria($entity_categoria);
                    }
                }
                $pelicula->setPelicula(true);

                $sinopsis = $this->crawler->filter('#synopsis-details > div.content-txt')->text();

                $pelicula->setSinopsis(trim($sinopsis));
                $this->crawler = $this->client->request('GET', $link->getUri() . 'reparto/');
                $this->crawler->filter('#content-layout > section > div > section.section.casting-director > div.gd > div > div > div > a')->each(function ($node) use ($pelicula) {
                    $link = $node->link();
                    $this->crawler = $this->client->click($link);
                    $persona = $this->crearPersona();
                    $etiqueta = $this->crearEtiqueta("Director");
                    $this->entityManager->persist($persona);
                    $this->entityManager->flush();
                    $this->crearMedioPersonaEtiqueta($etiqueta, $pelicula, $persona);
                });
                $this->crawler = $this->client->request('GET', $link->getUri() . 'reparto/');
                $this->crawler->filter('#content-layout > section > div > div.section.casting-list-gql')->children()->slice(1)->each(function ($node) use ($pelicula) {
                    if ($node->filter('span')->text() === 'Guionista') {
                        $nombre_persona = $node->filter('span:nth-child(2)')->text();
                        $persona = $this->personaRepository->findOneBy(["nombre" => $nombre_persona]);
                        if (!empty($persona)) {
                            $etiqueta = $this->crearEtiqueta("Guionista");
                            $this->crearMedioPersonaEtiqueta($etiqueta, $pelicula, $persona);
                        }
                    }
                });
                $this->crawler = $this->client->request('GET', $link->getUri() . 'reparto/');
                $this->crawler->filter('#content-layout > section > div > section.section.casting-actor > div.gd')->children()->each(function ($node) use ($pelicula) {
                    $link = $node->filter('div > div.meta-title > a')->link();
                    $this->crawler = $this->client->click($link);
                    $persona = $this->crearPersona();
                    $etiqueta = $this->crearEtiqueta("Actor");
                    $this->entityManager->persist($persona);
                    $this->entityManager->flush();
                    $this->crearMedioPersonaEtiqueta($etiqueta, $pelicula, $persona);
                });
                $this->entityManager->persist($pelicula);
                $this->entityManager->flush();
            });
            $io->writeln("Numero de pagina para Peliculas: $i");
        }
        $this->entityManager->persist($this->pagina);
        $this->entityManager->flush();
    }

    private function crearCategoria($nombre_categoria): Categoria
    {
        $categoria = $this->categoriaRepository->findOneBy(['nombre' => $nombre_categoria]);
        if (empty($categoria)) {
            $categoria = new Categoria();
            $categoria->setNombre($nombre_categoria);
            $this->entityManager->persist($categoria);
        }
        $this->pagina->addCategoria($categoria);
        $this->entityManager->persist($this->pagina);
        $this->entityManager->flush();
        return $categoria;
    }

    private function crearEtiqueta($nombre_etiqueta): Etiqueta
    {
        $etiqueta = $this->etiquetaRepository->findOneBy(['nombre' => $nombre_etiqueta]);
        if (empty($etiqueta)) {
            $etiqueta = new Etiqueta();
            $etiqueta->setNombre($nombre_etiqueta);
            $this->entityManager->persist($etiqueta);
        }
        $this->entityManager->flush();
        return $etiqueta;
    }

    private function crearPersona(): Persona
    {

        $nombre = $this->crawler->filter('#content-layout > section > div > div.titlebar-title.titlebar-title-lg');
        $persona = $this->personaRepository->findOneBy(['nombre' => $nombre->text()]);
        if (empty($persona)) {
            $persona = new Persona();
            $persona->setNombre($nombre->text());
        }
        $this->crawler->filter('#content-layout > div.section > div > section:nth-child(1) > div > div > div')->children()->each(function ($node) use ($persona) {
            if (trim($node->filter('span')->text()) == "Actividad" || trim($node->filter('span')->text()) == "Actividades") {
                if (trim($node->filter('span')->text()) == "Actividad") {
                    $nombre_etiqueta = trim(preg_replace('/\b' . preg_quote('Actividad', '/') . '\b/', '', $node->text()));
                    $etiqueta = $this->crearEtiqueta($nombre_etiqueta);
                    $persona->addEtiqueta($etiqueta);
                } else {
                    $nombre_etiquetas = trim(preg_replace('/\b(?:' . preg_quote('Actividades', '/') . '|' . preg_quote('más', '/') . ')\b/', '', $node->text()));
                    $nombre_etiquetas_array = explode(',', $nombre_etiquetas);
                    $nombre_etiquetas_array = array_map('trim', $nombre_etiquetas_array);
                    foreach ($nombre_etiquetas_array as $nombre_etiqueta) {
                        $etiqueta = $this->crearEtiqueta($nombre_etiqueta);
                        $persona->addEtiqueta($etiqueta);
                    }
                }
            }
            if (trim($node->filter('span')->text()) == "Nombre real") {
                $persona->setNombre($node->filter('h2')->text());
            }
            if (trim($node->filter('span')->text()) == "Nacionalidad" || trim($node->filter('span')->text()) == "Nacionalidades") {
                if (trim($node->filter('span')->text()) == "Nacionalidad") {
                    $nacionalidad = trim(preg_replace('/\b' . preg_quote('Nacionalidad', '/') . '\b/', '', $node->text()));
                } else {
                    $nacionalidad = trim(preg_replace('/\b' . preg_quote('Nacionalidades', '/') . '\b/', '', $node->text()));
                }

                $persona->setNacionalidad($nacionalidad);
            }
            if (trim($node->filter('span')->text()) == "Nacimiento") {
                $fechaNacimiento = $node->filter('span:nth-child(2)')->text();
                if (\DateTime::createFromFormat('Y', $fechaNacimiento))
                    $fechaNacimiento = \DateTime::createFromFormat('Y', $fechaNacimiento);
                else {
                    $fechaNacimiento = str_replace(array_keys($this->meses), array_values($this->meses), $fechaNacimiento);
                    $fechaNacimiento = \DateTime::createFromFormat('j \d\e F \d\e Y', $fechaNacimiento);
                }
                $persona->setFechaNacimiento($fechaNacimiento);
            }
        });

        return $persona;
    }

    private function crearMedioPersonaEtiqueta($etiqueta, $pelicula, $persona)
    {
        $medioPersonaEtiqueta = $this->medioPersonaEtiquetaRepository->findOneBy(['etiqueta' => $etiqueta, 'medio' => $pelicula, 'persona' => $persona]);
        if (empty($medioPersonaEtiqueta)) {
            $medioPersonaEtiqueta = new MedioPersonaEtiqueta();
            $medioPersonaEtiqueta->setEtiqueta($etiqueta);
            $medioPersonaEtiqueta->setMedio($pelicula);
            $medioPersonaEtiqueta->setPersona($persona);
            $this->entityManager->persist($medioPersonaEtiqueta);
            $this->entityManager->flush();
        }
    }
}