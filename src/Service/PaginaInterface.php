<?php
namespace App\Service;

interface PaginaInterface
{
    public function obtenerDatos($numero_pagina_personas, $numero_pagina_peliculas, $io);

}