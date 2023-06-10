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
        $this->erroMinimo = $config['valorErro'];
        $this->aprendizagem = $config['tx_Aprendizado'];
        $quantidadeNeuronios = ($entradas + $saidas) / 2;
        $quantidadeNeuronios = ceil($quantidadeNeuronios);
        $this->erroRede = 999;
        $this->camadaOculta = new CamadaOculta($quantidadeNeuronios);
        $this->camadaEntrada = new CamadaEntrada($entradas);
        $this->camadaSaida = new CamadaSaida(1);
        $this->dataSet = $dataSet;
        $this->criarConexoesEntradas();
        $this->criarConexoesSaida();
        $this->dataSet->montaMatrizSaida(count($this->camadaSaida->saidas), $this->camadaEntrada->entradas);
    }

    private function criarConexoesEntradas(): void
    {
        foreach ($this->camadaEntrada->entradas as $entrada) {
            foreach ($this->camadaOculta->neuronios as $neuronio) {
                $this->conexoesEntrada[] = new ConexaoEntrada($entrada, $neuronio);
            }
        }
    }

    private function criarConexoesSaida(): void
    {
        foreach ($this->camadaSaida->saidas as $saida) {
            foreach ($this->camadaOculta->neuronios as $neuronio) {
                $this->conexoesSaida[] = new ConexaoSaida($saida, $neuronio);
            }
        }
    }

    public function calcularNetOculta(): void
    {
        $conexoesEntradaOculta = $this->conexoesEntrada;
        foreach ($this->camadaOculta->neuronios as $neuronio) {
            $conexoes = ArrayHelper::findPares($conexoesEntradaOculta, $neuronio->id);
            foreach ($conexoes as $conexao) {
                $neuronio->net += $conexao->entrada->valor * $conexao->peso;
            }
        }
    }

    public function calcularSaidaOculta(): void
    {
        foreach ($this->camadaOculta->neuronios as $neuronio) {
            $neuronio->saida = $neuronio->net / 2;
        }
    }

    public function calcularNetSaida(): void
    {
        $conexoesOcultaSaida = $this->conexoesSaida;
        foreach ($this->camadaSaida->saidas as $saida) {
            $conexoes = ArrayHelper::findPares($conexoesOcultaSaida, $saida->id, 2);
            foreach ($conexoes as $conexao) {
                $saida->net += $conexao->neuronio->saida * $conexao->peso;
            }
        }
    }

    public function calcularSaidaSaida(): void
    {
        foreach ($this->camadaSaida->saidas as $saida) {
            $saida->valor = $saida->net / 2;
        }
    }

    public function calcularErroSaida(string $desejado): void
    {
        foreach ($this->camadaSaida->saidas as $key => $saida){
            $valorDesejado = $this->dataSet->buscarValorDesejado($key,$desejado);
            $saida->erro = (1 - $saida->valor) * 0.5;
        }
    }

    public function calcularErroRede(): void
    {
        $erro = 0;
        foreach ($this->camadaSaida->saidas as $saida){
            $erro +=  round((1 - $saida->valor) ** 2, 4);
        }
        $this->erroRede = 0.5 * $erro;
    }

    public function calcularErroCamadaOculta(): void
    {
        $neuronios = $this->camadaOculta->neuronios;
        $conexoesOcultaSaida = $this->conexoesSaida;
        $somatoria = 0;
        foreach ($neuronios as $neuronio) {
            $conexoes = ArrayHelper::findPares($conexoesOcultaSaida, $neuronio->id);
            foreach ($conexoes as $conexao){
                $somatoria += $conexao->saida->erro * $conexao->peso;
            }
            $neuronio->erro = $somatoria * 0.5;
        }
    }

    public function atualizarPesoSaida(): void
    {
        $conexoesOcultaSaida = $this->conexoesSaida;
        foreach ($conexoesOcultaSaida as $conexao) {
            $conexao->peso = $conexao->peso + $this->aprendizagem * $conexao->saida->erro * $conexao->neuronio->saida;
        }
    }

    public function atualizarPesoOculta(): void
    {
        $conexoesEntraOculta = $this->conexoesEntrada;
        foreach ($conexoesEntraOculta as $conexao) {
            $conexao->peso = $conexao->peso + $this->aprendizagem * $conexao->neuronio->erro * $conexao->entrada->valor;
        }
    }
}