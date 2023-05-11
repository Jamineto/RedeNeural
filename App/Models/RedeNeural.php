<?php

namespace App\Models;

use App\Helpers\ArrayHelper;
use App\Services\RedeNeuralService;

class RedeNeural
{
    public CamadaEntrada $camadaEntrada;
    public CamadaOculta $camadaOculta;
    public CamadaSaida $camadaSaida;
    public array $conexoesEntrada;
    public array $conexoesSaida;
    public int $aprendizagem;
    public float $erroRede;
    public DataSet $dataSet;

    public function __construct(int $entradas, int $saidas, bool $treinamento = false)
    {
        $treinamento ? $quantidadeNeuronios = 4 : $quantidadeNeuronios = ($entradas + $saidas) / 2;
        $this->camadaEntrada = new CamadaEntrada($entradas);
        $this->camadaOculta = new CamadaOculta($quantidadeNeuronios);
        $this->camadaSaida = new CamadaSaida(1);
        $this->criarConexoesEntradas();
        $this->criarConexoesSaida();
    }

    private function criarConexoesEntradas()
    {
        foreach($this->camadaEntrada->entradas as $entrada){
            foreach($this->camadaOculta->neuronios as $neuronio){
                $this->conexoesEntrada[] = new ConexaoEntrada($entrada,$neuronio);
            }
        }
    }

    private function criarConexoesSaida()
    {
        foreach($this->camadaSaida->saidas as $saida){
            foreach($this->camadaOculta->neuronios as $neuronio){
                $this->conexoesSaida[] = new ConexaoSaida($saida,$neuronio);
            }
        }
    }

    public function calcularNetsOculta()
    {
        $conexoes = $this->conexoesEntrada;
        $neuronios = $this->camadaOculta->neuronios;
        foreach ($neuronios as $neuronio){
            $conexoesNeuronio = ArrayHelper::findPares($conexoes,$neuronio->id);
            foreach ($conexoesNeuronio as $conexaoNeuronio){
                $neuronio->net += $conexaoNeuronio->peso * $conexaoNeuronio->entrada->valor;
            }
            $neuronio->saida = $neuronio->net / 2;
        }
    }

    public function calcularNetsSaida()
    {
        $conexoes = $this->conexoesSaida;
        $saidas = $this->camadaSaida->saidas;
        foreach ($saidas as $saida){
            $conexoesSaidas = ArrayHelper::findPares($conexoes,$saida->id);
            foreach ($conexoesSaidas as $conexaoSaida){
                $saida->net += $conexaoSaida->neuronio->saida * $conexaoSaida->peso;
            }
            $saida->valor = $saida->net / 2;
        }
    }

    public function calcularErroSaidas(int $valorDesejado)
    {
        $saidas = $this->camadaSaida->saidas;
        foreach ($saidas as $saida){
            //TODO: F(x)
            $saida->erro = ($valorDesejado - ($saida->valor)) * 0.5;
        }
    }

}