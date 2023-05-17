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
    public float $erroMinimo;

    public function __construct(int $entradas, int $saidas,DataSet $dataSet, bool $treinamento = false)
    {
        $treinamento ? $quantidadeNeuronios = 4 : $quantidadeNeuronios = ($entradas + $saidas) / 2;
        $this->erroMinimo = 0.1;
        $this->erroRede = 10000;
        $this->aprendizagem = 0.02;
        $this->camadaEntrada = new CamadaEntrada($entradas);
        $this->camadaOculta = new CamadaOculta($quantidadeNeuronios);
        $this->camadaSaida = new CamadaSaida(1);
        $this->dataSet = $dataSet;
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

    public function calcularErroSaidas(string $desejado)
    {
        $saidas = $this->camadaSaida->saidas;
        foreach ($saidas as $key => $saida){
            //TODO: F(x)
            $valorDesejado = $this->dataSet->buscarValorDesejado($key,$desejado);
            $saida->erro = ($valorDesejado - $saida->valor) * 0.5;
        }
    }

    public function calcularErroRede()
    {
        $erroSaidas = 0;
        foreach ($this->camadaSaida->saidas as $saida){
            $erroSaidas += pow($saida->erro,2);
        }
        $this->erroRede = 0.5 * $erroSaidas;
    }

    public function calcularErroOculta()
    {
        $conexoes = $this->conexoesSaida;
        $neuronios = $this->camadaOculta->neuronios;
        foreach ($neuronios as $neuronio){
            $conexoesNeuronio = ArrayHelper::findPares($conexoes,$neuronio->id);
            foreach ($conexoesNeuronio as $conexaoNeuronio){
                $neuronio->erro += $conexaoNeuronio->peso * $conexaoNeuronio->saida->erro;
            }
            $neuronio->erro = $neuronio->erro * 0.5;
        }
    }

    public function atualizarPesosConSaida()
    {
        $conexoes = $this->conexoesSaida;
        foreach ($conexoes as $conexao) {
            $conexao->peso = $conexao->peso + $this->aprendizagem * $conexao->saida->erro * $conexao->neuronio->saida;
        }
    }

    public function atualizarPesosConEntrada()
    {
        $conexoes = $this->conexoesEntrada;
        foreach ($conexoes as $conexao) {
            $conexao->peso = $conexao->peso + $this->aprendizagem * $conexao->neuronio->erro * $conexao->entrada->valor;
        }
    }


}