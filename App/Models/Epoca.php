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

    public function percorrer(): void
    {
        while ($this->redeNeural->erroRede > $this->redeNeural->erroMinimo && $this->countEpoca < 100) {
            $dataSet = $this->dataSet;
            $entradas = $this->redeNeural->camadaEntrada->entradas;
            foreach ($dataSet->data as $data) {
                for ($i = 0; $i < count($data) - 1; $i++) {
                    $entradas[$i]->valor = floatval($data[$i]);
                }
                $desejado = $data[count($data) - 1];
                $this->redeNeural->calcularNetOculta();
                $this->redeNeural->calcularSaidaOculta();
                $this->redeNeural->calcularNetSaida();
                $this->redeNeural->calcularSaidaSaida();
                $this->redeNeural->calcularErroSaida($desejado);
                $this->redeNeural->calcularErroRede();
                $this->redeNeural->calcularErroCamadaOculta();
//                $this->redeNeural->atualizarPesoSaida();
                $this->redeNeural->atualizarPesoOculta();
//                dd(json_encode($this->redeNeural));
            }
            $this->countEpoca = $this->countEpoca + 1;
        }
        dd($this->redeNeural);
    }
}