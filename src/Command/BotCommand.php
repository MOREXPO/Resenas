<?php

namespace App\Command;

use App\Repository\CategoriaRepository;
use App\Repository\EtiquetaRepository;
use App\Repository\InteligenciaArtificialRepository;
use App\Repository\ElencoRepository;
use App\Repository\MedioRepository;
use App\Repository\PaginaRepository;
use App\Repository\PersonaRepository;
use App\Repository\ResenaRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Doctrine\ORM\EntityManagerInterface;

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
        private MedioRepository $medioRepository,
        private PersonaRepository $personaRepository,
        private EtiquetaRepository $etiquetaRepository,
        private ElencoRepository $elencoRepository,
        private EntityManagerInterface $entityManager,
    ) {
        $this->container = new ContainerBuilder();
        $this->container->register('sensacine', 'App\Service\Sensacine')
            ->addArgument($this->categoriaRepository)
            ->addArgument($this->medioRepository)
            ->addArgument($this->personaRepository)
            ->addArgument($this->etiquetaRepository)
            ->addArgument($this->resenaRepository)
            ->addArgument($this->elencoRepository)
            ->addArgument($this->paginaRepository)
            ->addArgument($this->entityManager);
        parent::__construct();
    }
    protected function configure(): void
    {
        $this
            ->addArgument('pagina', InputArgument::OPTIONAL, 'Pagina a recorrer')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $nombrePagina = $input->getArgument('pagina');

        if (empty($nombrePagina)) {
            throw new \Exception("Tienes que pasarle como argumento la pagina a recorrer");
        }

        $paginaClass = $this->container->get($nombrePagina);
        $paginaClass->obtenerDatos();
        $paginaEntity = $this->paginaRepository->findOneBy(["nombre" => $nombrePagina]);
        $resenas = $this->resenaRepository->findBy(["pagina" => $paginaEntity]);
        $ias = $this->inteligenciaArtificialRepository->findAll();
        foreach ($resenas as $resena) {
            foreach ($ias as $ia) {
                $iaClass = $this->container->get($ia->getNombre());
                $iaClass->crearValoracion($resena);
            }
        }
        $io->success('Pagina rastreada con éxito');

        return Command::SUCCESS;
    }
}
