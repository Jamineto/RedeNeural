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
        $j = 0;
        while ($this->redeNeural->erroRede > $this->redeNeural->erroMinimo && $this->countEpoca < 1) {
            $dataSet = $this->dataSet;
            $entradas = $this->redeNeural->camadaEntrada->entradas;
            foreach ($dataSet->data as $data) {
                for ($i = 0; $i < count($data) - 1; $i++) {
                    $entradas[$i]->valor = floatval($data[$i]);
                }
                $desejado = $data[count($data) - 1];
                $this->redeNeural->calcularNetsOculta();
                $this->redeNeural->calcularNetsSaida();
                $this->redeNeural->calcularErroSaidas($desejado);
                $this->redeNeural->calcularErroRede();
                $this->redeNeural->calcularErroOculta();
                $this->redeNeural->atualizarPesosConSaida();
                $this->redeNeural->atualizarPesosConEntrada();
                dump($this->redeNeural->erroRede,$j + 1);
                $j++;
            }
            $this->countEpoca = $this->countEpoca + 1;
        }
        dd($this,$this->redeNeural->erroRede /100000,$this->redeNeural->erroMinimo,$this->countEpoca);
    }
}