<?php

namespace App\Models;

class Entrada 
{
    public int $id;
    public int $valor;

    public function __construct(int $id)
    {
        $this->id = $id;
        $this->valor = 0;
    }
}