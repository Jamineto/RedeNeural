<?php

namespace App\Services;

use App\Models\CamadaEntrada;
use App\Models\CamadaOculta;
use App\Models\ConexaoEntrada;
use App\Models\DataSet;
use App\Models\Epoca;
use App\Models\Neuronio;
use App\Models\RedeNeural;

class RedeNeuralService{

    public static function start(){
        //$rede = new RedeNeural(2,2);
    }
    public static function treinamento(){
        $dados = new DataSet('base_treinamento.csv');
        $rede = new RedeNeural($dados->entradas,$dados->saidas,$dados,true);
        $epoca = new Epoca($rede,$dados);
        $epoca->percorrer();
    }
}