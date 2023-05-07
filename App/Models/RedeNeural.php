<?php

namespace App\Models;

use App\Helpers\ArrayHelper;
use App\Services\RedeNeuralService;

class RedeNeural
{
    public CamadaEntrada $camadaEntrada;
    public CamadaOculta $camadaOculta;
    public array $conexoes;
    public int $entradas;
    public int $saidas;
    public int $aprendizagem;
    public float $erroRede;
    public DataSet $dataSet;

    public function __construct(int $entradas, int $saidas, bool $treinamento = false)
    {
        $treinamento ? $quantidadeNeuronios = 4 : $quantidadeNeuronios = ($entradas + $saidas) / 2;
        $this->camadaEntrada = new CamadaEntrada($entradas);
        $this->camadaOculta = new CamadaOculta($quantidadeNeuronios);
    }

    public function iniciarConexoes()
    {
        foreach($this->camadaEntrada->entradas as $entrada){
            foreach($this->camadaOculta->neuronios as $neuronio){
                $this->conexoes[] = new Conexao($entrada,$neuronio);
            }
        }
    }

    public function calcularNetsOculta()
    {
        $conexoes = $this->conexoes;
        $neuronios = $this->camadaOculta->neuronios;
        foreach ($neuronios as $neuronio){
            $conexoesNeuronio = ArrayHelper::findPares($conexoes,$neuronio->id);
            foreach ($conexoesNeuronio as $conexaoNeuronio){
                $neuronio->net += $conexaoNeuronio->peso * $conexaoNeuronio->entrada->valor;
            }
            $neuronio->saida = $neuronio->net / 2;
        }
    }

}