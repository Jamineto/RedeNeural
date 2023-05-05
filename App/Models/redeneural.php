<?php

namespace App\Models;

class RedeNeural 
{
    private CamadaEntrada $camadaEntrada;
    private array $conexoes;
    private CamadaOculta $camadaOculta;

    public function __construct(int $quantidadeNeuronios)
    {
        $this->camadaEntrada = new CamadaEntrada(2,[0,1]);
        $this->camadaOculta = new CamadaOculta(2,2);
        foreach($this->camadaOculta->neuronios as $neuronio){
            foreach($this->camadaEntrada->entradas as $entrada){
                $this->conexoes[] = new Conexao($entrada,$neuronio);
            }
        }
    }
}