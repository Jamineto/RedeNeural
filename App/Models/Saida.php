<?php

namespace App\Models;

class Saida
{
    public int $id;
    public float $valor;
    public float $net;
    public float $erro;

    public function __construct(int $id)
    {
        $this->id = $id;
        $this->net = 0;
        $this->valor = 0;
    }
}