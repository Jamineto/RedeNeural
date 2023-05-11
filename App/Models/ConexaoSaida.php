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
        $this->peso = $this->random_number(-1,1);
    }

    private function random_number(int $min,int $max): float
    {
        return round(mt_rand() / mt_getrandmax() * ($max - $min) + $min,5);
    }
}