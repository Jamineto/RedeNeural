<?php

namespace App\Models;

class ConexaoSaida
{
    public Saida $saida;
    public Neuronio $neuronio;
    public float $peso;

    public function __construct(Saida $saida, Neuronio $neuronio)
    {
        $this->saida = $saida;
        $this->neuronio = $neuronio;
        $this->peso = rand(0, 100) / 100;
    }
}