<?php

namespace App\Models;

class Neuronio 
{
    public int $id;
    public float $net;
    public float $saida;
    public float $erro;
    
    public function __construct(int $id)
    {
        $this->id = $id;
        $this->saida = 0;
        $this->net = 0;
        $this->erro = 0;
    }
}