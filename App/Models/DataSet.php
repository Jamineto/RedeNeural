<?php

namespace App\Models;

class DataSet
{
    public int $entradas;
    public int $saidas;
    public array $tipos;
    public array $data;
    public array $arrayTraducao;

    public function __construct(string $path)
    {
        $i = 0;
        $saidasDiff = [];
        $arquivo = fopen($path, 'r');
        while (!feof($arquivo)){
            $linha = fgetcsv($arquivo);
            if($linha != null){
                $saida = $linha[count($linha) - 1];
                if($i == 0){
                    $this->entradas = count($linha) - 1;
                }else{
                    if(!in_array($saida,$saidasDiff)){
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
        $this->setTraducao(0, count($this->tipos) - 1);
    }

    private function setTraducao(int $min, int $max){
        foreach ($this->tipos as $key => $tipo){
            $this->arrayTraducao[$tipo] = ($key - $min) / ($max - $min);
        }
        dd($this->arrayTraducao);
    }
}