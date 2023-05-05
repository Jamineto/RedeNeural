<?php

namespace App\Models;

class Entrada 
{
    public int $peso;

    public function __construct()
    {
        $this->peso = rand(0,1);
    }
}