<?php

namespace App\Models;

class CamadaOculta 
{
    public array $neuronios;
    public array $saidas;
    public array $nets;
    public array $erros;

    public function __construct(int $quantidadeNeuronios)
    {
        for($i = 0; $i < $quantidadeNeuronios; $i++){
            $this->neuronios[] = new Neuronio($i + 1);
        }
    }

    public function calcularNets()
    {

    }
}