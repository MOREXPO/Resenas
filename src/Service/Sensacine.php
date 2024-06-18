<?php
namespace App\Service;

use App\Entity\Categoria;
use App\Entity\ConfigurationPagina;
use App\Entity\Elenco;
use App\Entity\Etiqueta;
use App\Entity\Audiovisual;
use App\Entity\Medio;
use App\Entity\Persona;
use App\Entity\Resena;
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
    public function __construct(private $categoriaRepository, private $medioRepository, private $audiovisualRepository, private $personaRepository, private $etiquetaRepository, private $resenaRepository, private $elencoRepository, private $configurationPaginaRepository, private $paginaRepository)
    {
        $this->client = new Client();
        $this->pagina = $this->paginaRepository->findOneBy(["nombre" => "sensacine"]);
    }
    public function obtenerDatos($numero_pagina_personas, $numero_pagina_peliculas, $io)
    {

        if (isset($numero_pagina_personas)) {
            $configurationPagina = $this->configurationPaginaRepository->findOneBy(["pagina" => $this->pagina, "campo" => "numero_pagina_personas"]);
            if (!isset($configurationPagina)) {
                $configurationPagina = new ConfigurationPagina();
                $configurationPagina->setPagina($this->pagina);
                $configurationPagina->setCampo("numero_pagina_personas");
            }
            $this->crawler = $this->client->request('GET', $this->pagina->getDominio() . '/actores/todo/');
            $limite_pagina = $this->crawler->filter('div.pagination-item-holder')->children()->last()->text();
            for ($i = $numero_pagina_personas; $i <= $limite_pagina; $i++) {
                $this->crawler = $this->client->request('GET', $this->pagina->getDominio() . '/actores/todo/?page=' . $i);
                $this->crawler->filter('#content-layout > section.section.section-wrap.gd-3-cols.gd-gap-20.row-col-sticky > div.gd-col-middle > ul > li > div > div.meta > h2 > a')->each(function ($node) {
                    $link = $node->link();
                    $this->crawler = $this->client->click($link);
                    $persona = $this->crearPersona();
                    $this->personaRepository->save($persona, true);
                });
                $io->writeln("Numero de pagina para PERSONAS: $i");
                $configurationPagina->setNumero($i);
                $this->configurationPaginaRepository->save($configurationPagina, true);
            }
        }

        if (isset($numero_pagina_peliculas)) {
            $configurationPagina = $this->configurationPaginaRepository->findOneBy(["pagina" => $this->pagina, "campo" => "numero_pagina_peliculas"]);
            if (!isset($configurationPagina)) {
                $configurationPagina = new ConfigurationPagina();
                $configurationPagina->setPagina($this->pagina);
                $configurationPagina->setCampo("numero_pagina_peliculas");
            }
            $this->crawler = $this->client->request('GET', $this->pagina->getDominio() . '/peliculas/todas-peliculas/');
            $limite_pagina = $this->crawler->filter('div.pagination-item-holder')->children()->last()->text();
            for ($i = $numero_pagina_peliculas; $i <= $limite_pagina; $i++) {
                $this->crawler = $this->client->request('GET', $this->pagina->getDominio() . '/peliculas/todas-peliculas/?page=' . $i);
                $io->writeln("Numero de pagina para Peliculas: $i");
                $this->crawler->filter('#content-layout > section.section.section-wrap.gd-3-cols.gd-gap-20.row-col-sticky > div.gd-col-middle > ul > li > div > div.meta > h2 > a')->each(function ($node) use($io) {
                    $link = $node->link();
                    $this->crawler = $this->client->click($link);

                    //$titulo = trim(preg_replace('/\b' . preg_quote('Título original', '/') . '\b/', '', $this->crawler->filterXPath('//*[@id="content-layout"]/section/div/div[2]/div[1]/div/div[5]')->text()));
                    $titulo = $this->crawler->filter('#content-layout > section > div > div.card.entity-card > div.meta > div > div:nth-child(6)');
                    if ($titulo->count() === 0) {
                        $titulo = $this->crawler->filter('div.titlebar-title')->text();
                    } else {
                        $titulo = trim(preg_replace('/\b' . preg_quote('Título original', '/') . '\b/', '', $titulo->text()));
                    }
                    $io->writeln("Pelicula: $titulo");
                    $pelicula = $this->audiovisualRepository->findOneBy(['nombre' => $titulo]);
                    if (empty($pelicula)) {
                        $pelicula = new Audiovisual();
                        $medio = new Medio();
                        $medio->setTipo(1);
                        $pelicula->setNombre($titulo);
                        $pelicula->setMedio($medio);
                    }
                    $infoHeader = $this->crawler->filter('#content-layout > section > div > div.card.entity-card > div.meta > div > div.meta-body-item.meta-body-info');
                    $arrayHeader = explode('|', $infoHeader->text());
                    $reemplazos = ['en cines', 'a VOD', 'en Netflix', 'en Hbo max', 'en Disney+', 'en Amazon Prime Video'];
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
                            $pelicula->getMedio()->addCategoria($entity_categoria);
                            $this->pagina->addCategoria($entity_categoria);
                        }
                    } else {
                        $categorias = explode(',', $arrayHeader[1]);
                        foreach ($categorias as $categoria) {
                            $entity_categoria = $this->crearCategoria(trim($categoria));
                            $pelicula->getMedio()->addCategoria($entity_categoria);
                            $this->pagina->addCategoria($entity_categoria);
                        }
                    }
                    $pelicula->setPelicula(true);

                    $sinopsis = $this->crawler->filter('#synopsis-details > div.content-txt')->text();

                    $pelicula->setSinopsis(trim($sinopsis));
                    $this->medioRepository->save($pelicula->getMedio());
                    $this->audiovisualRepository->save($pelicula);

                    $this->crawler = $this->client->request('GET', $link->getUri() . 'criticas-espectadores/');

                    $limite_pagina = $this->crawler->filter('div.pagination-item-holder')->count() > 0 ? $this->crawler->filter('div.pagination-item-holder')->children()->last()->text() : 0;

                    for ($i = 1; $i <= $limite_pagina; $i++) {
                        $this->crawler = $this->client->request('GET', $link->getUri() . 'criticas-espectadores/?page=' . $i);
                        $this->crawler->filter('div.review-card')->each(function ($node) use ($pelicula) {
                            $this->crearResena($node, $pelicula);
                        });
                    }

                    $this->crawler = $this->client->request('GET', $link->getUri() . 'reparto/');
                    $this->crawler->filter('#content-layout > section > div > section.section.casting-director > div.gd > div > div > div > a')->each(function ($node) use ($pelicula) {
                        $link = $node->link();
                        $this->crawler = $this->client->click($link);
                        $persona = $this->crearPersona();
                        $etiqueta = $this->crearEtiqueta("Director");
                        $this->personaRepository->save($persona, true);
                        $this->crearElenco($etiqueta, $pelicula, $persona);
                    });

                    $this->crawler = $this->client->request('GET', $link->getUri() . 'reparto/');
                    $this->crawler->filter('#content-layout > section > div > div.section.casting-list-gql')->count() > 0 ? $this->crawler->filter('#content-layout > section > div > div.section.casting-list-gql')->children()->slice(1)->each(function ($node) use ($pelicula) {
                        if ($node->filter('span')->text() === 'Guionista') {
                            $nombre_persona = trim($node->filter('span:nth-child(2)')->text());
                            $persona = $this->personaRepository->findOneBy(["nombre" => $nombre_persona]);
                            if (!empty($persona)) {
                                $etiqueta = $this->crearEtiqueta("Guionista");
                                $this->crearElenco($etiqueta, $pelicula, $persona);
                            }
                        }
                    }) : null;

                    $this->crawler = $this->client->request('GET', $link->getUri() . 'reparto/');
                    $this->crawler->filter('#content-layout > section > div > section.section.casting-actor > div.gd')->count() > 0 ? $this->crawler->filter('#content-layout > section > div > section.section.casting-actor > div.gd')->children()->each(function ($node) use ($pelicula) {
                        if ($node->filter('div > div.meta-title > a')->count() > 0) {
                            $link = $node->filter('div > div.meta-title > a')->link();
                            $this->crawler = $this->client->click($link);
                            $persona = $this->crearPersona();
                            $etiqueta = $this->crearEtiqueta("Actor");
                            $this->personaRepository->save($persona, true);
                            $this->crearElenco($etiqueta, $pelicula, $persona);
                        }
                    }) : null;

                    $this->audiovisualRepository->save($pelicula, true);

                });
                $configurationPagina->setNumero($i);
                $this->configurationPaginaRepository->save($configurationPagina, true);
            }
        }

        $this->paginaRepository->save($this->pagina, true);
    }

    private function crearCategoria($nombre_categoria): Categoria
    {
        $categoria = $this->categoriaRepository->findOneBy(['nombre' => $nombre_categoria]);
        if (empty($categoria)) {
            $categoria = new Categoria();
            $categoria->setNombre($nombre_categoria);
            $this->categoriaRepository->save($categoria, true);
        }
        $this->pagina->addCategoria($categoria);
        $this->paginaRepository->save($this->pagina, true);
        return $categoria;
    }

    private function crearEtiqueta($nombre_etiqueta): Etiqueta
    {
        $etiqueta = $this->etiquetaRepository->findOneBy(['nombre' => $nombre_etiqueta]);
        if (empty($etiqueta)) {
            $etiqueta = new Etiqueta();
            $etiqueta->setNombre($nombre_etiqueta);
            $this->etiquetaRepository->save($etiqueta, true);
        }
        return $etiqueta;
    }

    private function crearResena($node, $pelicula)
    {
        $autor = trim($node->filter('div.review-card-aside > div > div > div')->text());

        $resena = $this->resenaRepository->findOneBy(['autor' => $autor, 'medio' => $pelicula->getMedio(), 'pagina' => $this->pagina]);
        if (empty($resena)) {
            $resena = new Resena();
            $resena->setAutor($autor);
            $resena->setMedio($pelicula->getMedio());
            $resena->setPagina($this->pagina);
        }

        $resena->setTexto(trim($node->filter('div.review-card-review-holder > div.content-txt.review-card-content')->text()));
        $this->resenaRepository->save($resena, true);
    }

    private function crearPersona(): Persona
    {

        $nombre = $this->crawler->filter('#content-layout > section > div > div.titlebar-title.titlebar-title-xl');
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
                if(gettype($fechaNacimiento)!=="boolean")$persona->setFechaNacimiento($fechaNacimiento);
            }
        });
        return $persona;
    }

    private function crearElenco($etiqueta, $pelicula, $persona)
    {
        $elenco = $this->elencoRepository->findOneBy(['etiqueta' => $etiqueta, 'audiovisual' => $pelicula, 'persona' => $persona]);
        if (empty($elenco)) {
            $elenco = new Elenco();
            $elenco->setEtiqueta($etiqueta);
            $elenco->setAudiovisual($pelicula);
            $elenco->setPersona($persona);
            $this->elencoRepository->save($elenco, true);
        }
    }
}