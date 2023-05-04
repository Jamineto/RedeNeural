<?php

namespace Services;

use Models\CamadaEntrada;
use Models\CamadaOculta;
use Models\Neuronio;

class RedeNeural{
    private CamadaEntrada $camadaEntradas;
    private CamadaOculta $camadaOculta;
    private int $entradas;
    private int $saidas;

    public function __construct(int $entradas, int $saidas)
    {
        $this->camadaOculta = new CamadaOculta(($entradas + $saidas / 2));
    }

    public function treinamento(string $arquivo){

    }
}