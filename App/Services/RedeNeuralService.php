<?php

namespace App\Services;

use App\Models\CamadaEntrada;
use App\Models\CamadaOculta;
use App\Models\ConexaoEntrada;
use App\Models\DataSet;
use App\Models\Epoca;
use App\Models\Neuronio;
use App\Models\RedeNeural;

class RedeNeuralService
{

    public static function executar($arquivo, $config): array
    {
        if (preg_match("/(treinamento)/i", $arquivo) === 1) {
            $dados = new DataSet($arquivo, true, []);
            $rede = new RedeNeural($dados->entradas, $dados->saidas, $dados, true, $config);
            $epoca = new Epoca($rede, $dados);
            return $epoca->treinamento();
        } else {
            $jsonData = file_get_contents('configuracoes.txt');
            $configuracoes = json_decode($jsonData,true);
            $config = array_merge($config,$configuracoes);
            $dados = new DataSet($arquivo,false,$configuracoes);
            $rede = new RedeNeural($dados->entradas, $dados->saidas, $dados, false, $config);
            $epoca = new Epoca($rede, $dados);
            return $epoca->executar();
        }
    }
}