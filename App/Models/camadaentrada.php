<?php

namespace App\Models;

class CamadaEntrada 
{
    public array $entradas;

    public function __construct(int $quantidadeEntradas)
    {
        for($i = 0; $i < $quantidadeEntradas; $i++){
            $this->entradas[] = new Entrada();
        }
    }
}