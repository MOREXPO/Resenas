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
        $this->crawler = $this->client->request('GET', $this->pagina->getDominio());
    }
    public function obtenerDatos()
    {
        $this->obtenerCategorias();
    }

    private function obtenerCategorias()
    {
        $this->crawler->filter('ul[data-name="Por género"] > li > a')->each(function ($node) {
            $textNode = trim(preg_replace('/\([^)]*\)/', '', $node->text()));
            $categoria = $this->categoriaRepository->findOneBy(['nombre' => $textNode]);
            if (empty($categoria)) {
                $categoria = new Categoria();
                $categoria->setNombre($textNode);
                $this->entityManager->persist($categoria);
            }
            $this->pagina->addCategoria($categoria);
            $this->entityManager->persist($this->pagina);

            $link = $node->link();
            $this->crawler = $this->client->click($link);
            $this->obtenerPeliculas($categoria);
        });
        $this->entityManager->flush();
    }

    private function obtenerPeliculas($categoria)
    {
        $this->crawler->filter('div.gd-col-middle > ul > li > div.entity-card > div.meta > h2 > a')->each(function ($node) {
            $link = $node->link();
            $this->crawler = $this->client->click($link);
            $titulo = trim(preg_replace('/\b' . preg_quote('Título original', '/') . '\b/', '', $this->crawler->filterXPath('//*[@id="content-layout"]/section/div/div[2]/div[1]/div/div[5]')->text()));
            $pelicula = $this->medioRepository->findOneBy(['nombre' => $titulo]);
            if (empty($pelicula)) {
                $pelicula = new Medio();
                $pelicula->setNombre($titulo);
            }
            $infoHeader = $this->crawler->filterXPath('//*[@id="content-layout"]/section/div/div[2]/div[1]/div/div[1]');
            $arrayHeader = explode('/', $infoHeader->text());
            $fechaLanzamiento = trim(preg_replace('/\b' . preg_quote('en cines', '/') . '\b/', '', $arrayHeader[0]));
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
            } else {
                dd("No tiene duracion");
            }
            $pelicula->setPelicula(true);
            $sinopsis = $this->crawler->filterXPath('//*[@id="synopsis-details"]/div[2]/text()[1]')->text();
            $pelicula->setSinopsis(trim($sinopsis));
            $categorias = explode(',', $arrayHeader[2]);
            foreach ($categorias as $categoria) {
                $entity_categoria = $this->categoriaRepository->findOneBy(['nombre' => trim($categoria)]);
                $pelicula->addCategoria($entity_categoria);
            }
            $directores = $this->crawler->filter('#content-layout > section > div > div.card.entity-card.entity-card-list.cf.entity-card-overview > div.meta > div > div:nth-child(2)')->children()->each(function ($node) {
                dump($node->text());
            });
            $escritores = $this->crawler->filter('#content-layout > section > div > div.card.entity-card.entity-card-list.cf.entity-card-overview > div.meta > div > div:nth-child(3)')->children()->each(function ($node) {
                dump($node->text());
            });
            $reparto = $this->crawler->filter('#content-layout > section > div > div.card.entity-card.entity-card-list.cf.entity-card-overview > div.meta > div > div:nth-child(4)')->children()->each(function ($node) {
                dump($node->text());
            });
            dd("fin");
            $this->entityManager->persist($pelicula);
        });
        $this->entityManager->flush();
    }

    private function crearPersona(): Persona
    {
        $nombre = $this->crawler->filterXPath('//*[@id="actorHeader"]/div[2]/div[2]/h1/text()');
        $persona = $this->personaRepository->findOneBy(['nombre' => $nombre->text()]);
        if (empty($persona)) {
            $persona = new Persona();
            $persona->setNombre($nombre->text());
        }
        $fechaNacimiento = $this->crawler->filterXPath('//*[@id="actorHeader"]/div[2]/div[2]/dl/dd[1]/time')->attr('datetime');
        $fechaNacimiento = \DateTime::createFromFormat("Y-m-d\TH:i:sP", $fechaNacimiento);
        $persona->setFechaNacimiento($fechaNacimiento);
        $nacionalidad = $this->crawler->filterXPath('//*[@id="actorHeader"]/div[2]/div[2]/dl/dd[2]');
        $persona->setNacionalidad($nacionalidad->text());

        return $persona;
    }

    private function crearEtiquetas(): array
    {
        $nombres = $this->crawler->filterXPath('//*[@id="actorHeader"]/div[2]/div[2]/h1/span');
        $nombre_etiquetas = explode(",", $nombres->text());
        $etiquetas = [];
        foreach ($nombre_etiquetas as $nombre_etiqueta) {
            $etiqueta = $this->etiquetaRepository->findOneBy(['nombre' => $nombre_etiqueta]);
            if (empty($etiqueta)) {
                $etiqueta = new Etiqueta();
                $etiqueta->setNombre(trim($nombre_etiqueta));
            }
            $etiquetas[] = $etiqueta;
        }
        return $etiquetas;
    }

    private function crearMedioPersonaEtiqueta($etiqueta, $pelicula, $persona)
    {
        $medioPersonaEtiqueta = $this->medioPersonaEtiquetaRepository->findOneBy(['etiqueta' => $etiqueta, 'medio' => $pelicula]);
        if (empty($medioPersonaEtiqueta)) {
            $medioPersonaEtiqueta = new MedioPersonaEtiqueta();
            $medioPersonaEtiqueta->setEtiqueta($etiqueta);
            $medioPersonaEtiqueta->setMedio($pelicula);
        }
        $medioPersonaEtiqueta->addPersona($persona);
    }
}