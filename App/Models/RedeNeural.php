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
    public float $aprendizagem;
    public float $erroRede;
    public DataSet $dataSet;
    public float $erroMinimo;

    public function __construct(int $entradas, int $saidas, DataSet $dataSet, bool $treinamento = false, array $config = [])
    {
        if ($treinamento === false) {
            $quantidadeNeuronios = 4;
        } else {
            $this->erroMinimo = $config['valorErro'];
            $this->aprendizagem = $config['tx_Aprendizado'];
            $quantidadeNeuronios = ($entradas + $saidas) / 2;
        }
        $quantidadeNeuronios = ceil($quantidadeNeuronios);
        $this->erroRede = 999;
        $this->camadaOculta = new CamadaOculta($quantidadeNeuronios);
        $this->camadaEntrada = new CamadaEntrada($entradas);
        $this->camadaSaida = new CamadaSaida(1);
        $this->dataSet = $dataSet;
        $this->criarConexoesEntradas();
        $this->criarConexoesSaida();
    }

    private function criarConexoesEntradas()
    {
        foreach ($this->camadaEntrada->entradas as $entrada) {
            foreach ($this->camadaOculta->neuronios as $neuronio) {
                $this->conexoesEntrada[] = new ConexaoEntrada($entrada, $neuronio);
            }
        }
    }

    private function criarConexoesSaida()
    {
        foreach ($this->camadaSaida->saidas as $saida) {
            foreach ($this->camadaOculta->neuronios as $neuronio) {
                $this->conexoesSaida[] = new ConexaoSaida($saida, $neuronio);
            }
        }
    }

    public function calcularNetsOculta()
    {
        $conexoes = $this->conexoesEntrada;
        $neuronios = $this->camadaOculta->neuronios;
        foreach ($neuronios as $neuronio) {
            $conexoesNeuronio = ArrayHelper::findPares($conexoes, $neuronio->id);
            foreach ($conexoesNeuronio as $conexaoNeuronio) {
                $neuronio->net += $conexaoNeuronio->peso * $conexaoNeuronio->entrada->valor;
            }
            $neuronio->saida = $neuronio->net / 2;
        }
    }

    public function calcularNetsSaida()
    {
        $conexoes = $this->conexoesSaida;
        $saidas = $this->camadaSaida->saidas;
        foreach ($saidas as $saida) {
            $conexoesSaidas = ArrayHelper::findPares($conexoes, $saida->id);
            foreach ($conexoesSaidas as $conexaoSaida) {
                $saida->net += $conexaoSaida->neuronio->saida * $conexaoSaida->peso;
            }
            $saida->valor = $saida->net / 2;
        }
    }

    public function calcularErroSaidas(string $desejado)
    {
        $saidas = $this->camadaSaida->saidas;
        foreach ($saidas as $key => $saida) {
            //TODO: F(x)
            $valorDesejado = $this->dataSet->buscarValorDesejado($key, $desejado);
            $saida->erro = round(($valorDesejado - $saida->valor) * 0.5,10);
        }
    }

    public function calcularErroRede()
    {
        $erroSaidas = 0;
        foreach ($this->camadaSaida->saidas as $saida) {
            $erroSaidas += $saida->erro;
        }
        $this->erroRede = round(0.5 * $erroSaidas,10);
    }

    public function calcularErroOculta()
    {
        $conexoes = $this->conexoesSaida;
        $neuronios = $this->camadaOculta->neuronios;
        foreach ($neuronios as $neuronio) {
            $conexoesNeuronio = ArrayHelper::findPares($conexoes, $neuronio->id);
            foreach ($conexoesNeuronio as $conexaoNeuronio) {
                $neuronio->erro += $conexaoNeuronio->peso * $conexaoNeuronio->saida->erro;
            }
            $neuronio->erro = round($neuronio->erro * 0.5,10);
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