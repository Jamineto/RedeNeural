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
        $this->peso =  rand(0, 100) / 100;
    }

}