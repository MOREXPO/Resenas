<?php
namespace App\Service;

use App\Entity\Valoracion;
use App\Repository\InteligenciaArtificialRepository;
use App\Repository\ValoracionRepository;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class NltkIA implements InteligenciaArtificialInterface
{

    public function __construct(private InteligenciaArtificialRepository $inteligenciaArtificialRepository, private ValoracionRepository $valoracionRepository)
    {

    }
    public function crearValoracion($resena, $io): Valoracion
    {
        $ia = $this->inteligenciaArtificialRepository->findOneBy(["nombre" => "nltkia"]);
        $valoracion = $this->valoracionRepository->findOneBy(["resena" => $resena, "inteligenciaArtificial" => $ia]);
        if (empty($valoracion)) {
            $valoracion = new Valoracion();
            $valoracion->setResena($resena);
            $valoracion->setInteligenciaArtificial($ia);
        }

        $process = new Process(['python3', '/var/www/symfony/python_IA/nltkIA.py', $resena->getId()]);
        $process->setTimeout(3600);
        $io->writeln("Ejecutando proceso python con Nltk");

        $process->start();
        try {
            $process->wait(function ($type, $buffer) use ($io) {
                if (Process::ERR === $type) {
                    $io->writeln('ERR > ' . $buffer);
                } else {
                    $io->writeln('OUT > ' . $buffer);
                }
            });
            while ($process->isRunning()) {
                $io->writeln("En espera de finalización del proceso");
            }
            if (preg_match('/La puntuacion media es de (-?\d+\.\d+)/', $process->getOutput(), $matches)) {
                // $matches[1] contiene el número como string
                $puntuacion_media = (float) $matches[1];
                $valoracion->setCalificacion($puntuacion_media);
            }
        } catch (ProcessFailedException $exception) {
            $io->writeln($exception->getMessage());
        }
        return $valoracion;
    }
}