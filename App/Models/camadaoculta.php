<?php

namespace App\Models;

class CamadaOculta 
{
    public array $neuronios;

    public function __construct(int $quantidadeEntradas, int $quantidadeSaidas)
    {
        for($i = 0; $i < ($quantidadeEntradas + $quantidadeSaidas) / 2; $i++){
            $this->neuronios[] = new Neuronio();
        }
    }
}