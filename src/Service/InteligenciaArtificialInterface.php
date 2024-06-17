<?php
namespace App\Service;
use App\Entity\Valoracion;

interface InteligenciaArtificialInterface
{
    public function crearValoracion($resena,$io):Valoracion;
}