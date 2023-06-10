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

    public static function executar($arquivo, $config): void
    {
        $dados = new DataSet($arquivo);
        if (preg_match("/(treinamento)/i", $arquivo) === 1)
            $rede = new RedeNeural($dados->entradas, $dados->saidas, $dados, true, $config);
        else
            $rede = new RedeNeural($dados->entradas, $dados->saidas, $dados, false);
        $epoca = new Epoca($rede, $dados);
        $epoca->percorrer();
    }
}