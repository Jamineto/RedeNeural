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
    public string $tipoFuncao;
    public int $limiteEpoca;

    public function __construct(int $entradas, int $saidas, DataSet $dataSet, bool $treinamento = false, array $config = [])
    {
        $quantidadeNeuronios = ($entradas + $saidas) / 2;
        $quantidadeNeuronios = ceil($quantidadeNeuronios);
        $this->erroRede = 999;
        $this->camadaOculta = new CamadaOculta($quantidadeNeuronios);
        $this->camadaEntrada = new CamadaEntrada($entradas);
        $this->camadaSaida = new CamadaSaida(1);
        $this->dataSet = $dataSet;
        $this->criarConexoesEntradas($treinamento, $config);
        $this->criarConexoesSaida($treinamento, $config);
        $this->dataSet->montaMatrizSaida(count($this->camadaSaida->saidas), $this->camadaEntrada->entradas);
        $this->tipoFuncao = $config['group1'];
        if ($treinamento) {
            $this->erroMinimo = $config['valorErro'];
            $this->aprendizagem = $config['tx_Aprendizado'];
            $this->limiteEpoca = $config['numInt'];
        }
    }

    private function criarConexoesEntradas(bool $treinamento = false, array $configuracoes = []): void
    {
        $i = 0;
        foreach ($this->camadaEntrada->entradas as $entrada) {
            foreach ($this->camadaOculta->neuronios as $neuronio) {
                $conexao = new ConexaoEntrada($entrada, $neuronio);
                if (!$treinamento) {
                    $conexao->peso = $configuracoes['pesos']['entradas'][$i];
                }
                $this->conexoesEntrada[] = $conexao;
                $i++;
            }
        }
    }

    private function criarConexoesSaida(bool $treinamento = false, array $configuracoes = []): void
    {
        $i = 0;
        foreach ($this->camadaSaida->saidas as $saida) {
            foreach ($this->camadaOculta->neuronios as $neuronio) {
                $conexao = new ConexaoSaida($saida, $neuronio);
                if (!$treinamento) {
                    $conexao->peso = $configuracoes['pesos']['saidas'][$i];
                }
                $this->conexoesSaida[] = $conexao;
                $i++;
            }
        }
    }

    public function calcularNetOculta(): void
    {
        $conexoesEntradaOculta = $this->conexoesEntrada;
        foreach ($this->camadaOculta->neuronios as $neuronio) {
            $conexoes = ArrayHelper::findPares($conexoesEntradaOculta, $neuronio->id);
            if($this->tipoFuncao === 'linear') {
                foreach ($conexoes as $conexao) {
                    $neuronio->net += floatval(rtrim(number_format($conexao->entrada->valor * $conexao->peso, 4), '0'));
                }
            }else{
                foreach ($conexoes as $conexao) {
                    $neuronio->net += $conexao->entrada->valor * $conexao->peso;
                }
            }
        }
    }

    public function calcularSaidaOculta(): void
    {
        if($this->tipoFuncao === 'linear') {
            foreach ($this->camadaOculta->neuronios as $neuronio) {
                $neuronio->saida = floatval(rtrim(number_format($this->formula($neuronio->net, 1), 4), '0'));
            }
        }else {
            foreach ($this->camadaOculta->neuronios as $neuronio) {
                $neuronio->saida = $this->formula($neuronio->net, 1);
            }
        }
    }

    public function calcularNetSaida(): void
    {
        $conexoesOcultaSaida = $this->conexoesSaida;
        foreach ($this->camadaSaida->saidas as $saida) {
            $conexoes = ArrayHelper::findPares($conexoesOcultaSaida, $saida->id, 2);
            if($this->tipoFuncao === 'linear') {
                foreach ($conexoes as $conexao) {
                    $saida->net += floatval(rtrim(number_format($conexao->neuronio->saida * $conexao->peso, 4), '0'));
                }
            }else {
                foreach ($conexoes as $conexao) {
                    $saida->net += $conexao->neuronio->saida * $conexao->peso;
                }
            }
        }
    }

    public function calcularSaidaSaida(): void
    {
        if($this->tipoFuncao === 'linear'){
            foreach ($this->camadaSaida->saidas as $saida) {
                $saida->valor = floatval(rtrim(number_format($saida->net / 2, 4), '0'));
            }
        }else{
            foreach ($this->camadaSaida->saidas as $saida) {
                $saida->valor = $saida->net / 2;
            }
        }
    }

    public function calcularErroSaida(string $desejado): void
    {
        foreach ($this->camadaSaida->saidas as $key => $saida) {
            $valorDesejado = $this->dataSet->buscarValorDesejado($key, $desejado);
            $saida->erro = (1 - $saida->valor) * $this->formula($saida->net, 2);
        }
    }

    public function calcularErroRede(): void
    {
        $erro = 0;
        foreach ($this->camadaSaida->saidas as $saida) {
            $erro += (1 - $saida->valor) ** 2;
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
            foreach ($conexoes as $conexao) {
                $somatoria += $conexao->saida->erro * $conexao->peso;
            }
            $neuronio->erro = $somatoria * $this->formula($neuronio->net,2);
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
            $conexao->peso =$conexao->peso + $this->aprendizagem * $conexao->neuronio->erro * $conexao->entrada->valor;
        }
    }

    public function formula($net, $tipo): float
    {
        $retorno = 0;
        switch ($this->tipoFuncao) {
            case 'linear':
                if ($tipo === 1) {
                    $retorno = $net / 10;
                } else
                    $retorno = 1 / 10;
                break;
            case 'logistica':
                $valor = 1 / (1 + exp(-$net));
                if ($tipo === 1) {
                    $retorno = $valor;
                } else
                    $retorno = $valor * (1 - $valor);
                break;
            case 'hiperbolica':
                $valor = tanh($net);
                if ($tipo === 1) {
                    $retorno = $valor;
                } else
                    $retorno = 1 - ($valor ** 2);
                break;
        }
        return $retorno;
    }
}