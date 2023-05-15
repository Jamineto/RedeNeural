<?php

namespace App\Models;

class DataSet
{
    public int $entradas;
    public int $saidas;
    public array $tipos;
    public array $data;
    public array $parametros;
    public array $matrizSaidas;

    public function __construct(string $path)
    {
        $i = 0;
        $saidasDiff = [];
        $arquivo = fopen($path, 'r');
        while (!feof($arquivo)) {
            $linha = fgetcsv($arquivo);
            if ($linha != null) {
                $saida = $linha[count($linha) - 1];
                if ($i == 0) {
                    $this->entradas = count($linha) - 1;
                } else {
                    if (!in_array($saida, $saidasDiff)) {
                        $saidasDiff[] = $saida;
                    }
                    $this->data[] = $linha;
                }
            }
            $i++;
        }
        fclose($arquivo);
        $this->tipos = $saidasDiff;
        $this->saidas = count($saidasDiff);
        $this->parametrosNormalizacao();
        $this->montaMatrizSaida();
    }

    private function parametrosNormalizacao()
    {
        $maior = [];
        $menor = [];
        foreach ($this->data as $dataL) {
            for ($i = 0; $i < $this->entradas; $i++) {
                $valor = floatval($dataL[$i]);
                if(isset($maior[$i])){
                    if ($maior[$i] < $valor) {
                        $maior[$i] = $valor;
                    }
                }else
                    $maior[$i] = $valor;
                if(isset($menor[$i])){
                    if ($valor < $menor[$i]) {
                        $menor[$i] = $valor;
                    }
                }else
                    $menor[$i] = $valor;

            }
            $this->parametros = [
                'maior' => $maior,
                'menor' => $menor
            ];
        }
        $this->normalizarDados();
    }

    private function normalizarDados(): void
    {
        foreach ($this->data as $key => $data) {
            for ($i = 0; $i < count($data) - 1; $i++) {
                $this->data[$key][$i] = floatval($this->normalizar(floatval($this->data[$key][$i]),$i));
            }
        }
    }

    private function normalizar(float $valor, int $i): string
    {
        return number_format(($valor - $this->parametros['menor'][$i]) / ($this->parametros['maior'][$i] - $this->parametros['menor'][$i]), 2);
    }
    // private function setTraducao(int $min, int $max){
    //     foreach ($this->tipos as $key => $tipo){
    //         $this->arrayTraducao[$tipo] = ($key - $min) / ($max - $min);
    //     }
    // }

    public function montaMatrizSaida(){
        $tipos = $this->tipos;
        for ($i = 0; $i < count($tipos); $i++) {
            for ($j = 0; $j < count($tipos); $j++) {
                if($tipos[$i] === $tipos[$j])
                {
                    $this->matrizSaidas[$i][$j] = 1;
                }else
                    $this->matrizSaidas[$i][$j] = 0;
            }
        }
    }
}