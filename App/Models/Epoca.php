<?php

namespace App\Models;

use Symfony\Component\VarDumper\Cloner\Data;

class Epoca
{
    public RedeNeural $redeNeural;
    public DataSet $dataSet;
    public int $countEpoca;

    public function __construct(RedeNeural $redeNeural, DataSet $dataSet)
    {
        $this->redeNeural = $redeNeural;
        $this->dataSet = $dataSet;
        $this->countEpoca = 0;
    }

    public function percorrer()
    {
        $dataSet = $this->dataSet;
        $entradas = $this->redeNeural->camadaEntrada->entradas;
        foreach ($dataSet->data as $data){
            for($i = 0; $i < count($data) - 1; $i++){
                $entradas[$i]->valor = intval($data[$i]);
            }
            $this->redeNeural->calcularNetsOculta();
            $this->redeNeural->calcularNetsSaida();
            $this->redeNeural->calcularErroSaidas();
            dd($this);
        }
        $this->countEpoca++;
        dd($this);
    }
}