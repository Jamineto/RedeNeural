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

    public function treinamento(): array
    {
        $historico = [];
        while ($this->redeNeural->erroRede > $this->redeNeural->erroMinimo && $this->countEpoca < $this->redeNeural->limiteEpoca) {
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
                $this->redeNeural->atualizarPesoSaida();
                $this->redeNeural->atualizarPesoOculta();
            }
            $historico[] = $this->redeNeural->erroRede;
            $this->countEpoca = $this->countEpoca + 1;
        }
        $this->guardarValores();
        return $historico;
    }

    public function executar(): void
    {
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
        }
    }

    private function guardarValores(): void
    {
        $data = [
            'pesos' => [
                'entradas' => [],
                'saidas' => []
            ],
            'parametros' => []
        ];
        foreach ($this->redeNeural->conexoesEntrada as $conexao){
            $data['pesos']['entradas'][] = $conexao->peso;
        }
        foreach ($this->redeNeural->conexoesSaida as $conexao){
            $data['pesos']['saidas'][] = $conexao->peso;
        }
        $data['parametros'] = $this->dataSet->parametros;
        $data = json_encode($data);
        file_put_contents('configuracoes.txt', $data);
    }

    private function matrizConfusao(): array
    {
        //
    }
}