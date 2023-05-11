<?php

namespace App\Models;

class CamadaSaida
{
    public array $saidas;

    public function __construct(int $quantidadeSaidas)
    {
        for($i = 0; $i < $quantidadeSaidas; $i++){
            $this->saidas[] = new Saida($i + 1);
        }
    }
}