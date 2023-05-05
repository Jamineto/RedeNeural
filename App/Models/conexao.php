<?php

namespace App\Models;

class Conexao 
{
    private Entrada $entrada;
    private Neuronio $neuronio;
    private float $peso;

    public function __construct(Entrada $entrada, Neuronio $neuronio)
    {
        $this->peso = rand(-1,1);
        $this->entrada = $entrada;
        $this->neuronio = $neuronio;
    }
}