<?php

namespace App\Command;

use App\Entity\ConfigurationPagina;
use App\Repository\CategoriaRepository;
use App\Repository\ConfigurationPaginaRepository;
use App\Repository\EtiquetaRepository;
use App\Repository\InteligenciaArtificialRepository;
use App\Repository\ElencoRepository;
use App\Repository\AudiovisualRepository;
use App\Repository\MedioRepository;
use App\Repository\PaginaRepository;
use App\Repository\PersonaRepository;
use App\Repository\ResenaRepository;
use App\Repository\ValoracionRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

#[AsCommand(
    name: 'bot',
    description: 'Comando para obtener todos los datos de una pagina de reseñas',
)]
class BotCommand extends Command
{
    private $container;
    public function __construct(
        private ResenaRepository $resenaRepository,
        private PaginaRepository $paginaRepository,
        private InteligenciaArtificialRepository $inteligenciaArtificialRepository,
        private CategoriaRepository $categoriaRepository,
        private AudiovisualRepository $audiovisualRepository,
        private MedioRepository $medioRepository,
        private PersonaRepository $personaRepository,
        private EtiquetaRepository $etiquetaRepository,
        private ElencoRepository $elencoRepository,
        private ValoracionRepository $valoracionRepository,
        private ConfigurationPaginaRepository $configurationPaginaRepository,
    ) {
        $this->container = new ContainerBuilder();
        $this->container->register('sensacine', 'App\Service\Sensacine')
            ->addArgument($this->categoriaRepository)
            ->addArgument($this->medioRepository)
            ->addArgument($this->audiovisualRepository)
            ->addArgument($this->personaRepository)
            ->addArgument($this->etiquetaRepository)
            ->addArgument($this->resenaRepository)
            ->addArgument($this->elencoRepository)
            ->addArgument($this->configurationPaginaRepository)
            ->addArgument($this->paginaRepository);
        $this->container->register('nltkia', 'App\Service\NltkIA')
            ->addArgument($this->inteligenciaArtificialRepository)
            ->addArgument($this->valoracionRepository);
        $this->container->register('textblobia', 'App\Service\TextBlobIA')
            ->addArgument($this->inteligenciaArtificialRepository)
            ->addArgument($this->valoracionRepository);
        parent::__construct();
    }
    protected function configure(): void
    {
        $this
            ->addArgument('pagina', InputArgument::OPTIONAL, 'Pagina a recorrer')
            ->addArgument('ia', InputArgument::OPTIONAL, 'inteligencia artificial a usar')
            ->addOption('numero_pagina_personas', null, InputOption::VALUE_REQUIRED, 'importar personas desde el numero de pagina indicado', null)
            ->addOption('numero_pagina_peliculas', null, InputOption::VALUE_REQUIRED, 'importar personas desde el numero de pagina indicado', null)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $nombrePagina = $input->getArgument('pagina');
        $inteligenciaArtificial = $input->getArgument('ia');

        if (empty($nombrePagina)) {
            throw new \Exception("Tienes que pasarle como argumento la pagina a recorrer");
        }

        $paginaClass = $this->container->get($nombrePagina);
        $paginaEntity = $this->paginaRepository->findOneBy(["nombre" => $nombrePagina]);
        $numero_pagina_personas = $input->getOption('numero_pagina_personas');

        if (!isset($numero_pagina_personas)) {
            $numero_pagina_personas = $this->configurationPaginaRepository->findOneBy(["pagina" => $paginaEntity, "campo" => "numero_pagina_personas"]);
            if ($numero_pagina_personas)
                $numero_pagina_personas = $numero_pagina_personas->getNumero();
        }

        $numero_pagina_peliculas = $input->getOption('numero_pagina_peliculas');
        if (!isset($numero_pagina_peliculas)) {
            $numero_pagina_peliculas = $this->configurationPaginaRepository->findOneBy(["pagina" => $paginaEntity, "campo" => "numero_pagina_peliculas"]);
            if ($numero_pagina_peliculas)
                $numero_pagina_peliculas = $numero_pagina_peliculas->getNumero();
        }

        $paginaClass->obtenerDatos($numero_pagina_personas, $numero_pagina_peliculas, $io);
        $resenas = $this->resenaRepository->findBy(["pagina" => $paginaEntity]);
        $ias = $this->inteligenciaArtificialRepository->findAll();
        foreach ($ias as $ia) {
            if ($ia->getNombre() == $inteligenciaArtificial || empty($inteligenciaArtificial)) {
                $io->writeln($ia->getNombre() . " esta valorando las reseñas");
                foreach ($resenas as $resena) {
                    $iaClass = $this->container->get($ia->getNombre());
                    $valoracion = $iaClass->crearValoracion($resena, $io);
                    $this->valoracionRepository->save($valoracion, true);
                }
            }
        }
        $io->success('Pagina rastreada con éxito');

        return Command::SUCCESS;
    }
}
