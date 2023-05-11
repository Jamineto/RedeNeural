<?php

namespace App\Models;

class ConexaoEntrada
{
    public Entrada $entrada;
    public Neuronio $neuronio;
    public float $peso;

    public function __construct(Entrada $entrada, Neuronio $neuronio)
    {
        $this->entrada = $entrada;
        $this->neuronio = $neuronio;
        //$this->peso = $this->random_number(-1,1);
        $pesos = [1.1,-1.4];
        $this->peso = $pesos[rand(0,1)];
    }

    private function random_number($min, $max): float
    {
        return round(mt_rand() / mt_getrandmax() * ($max - $min) + $min,5);
    }
}