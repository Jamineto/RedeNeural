<?php

use App\Helpers\FileUploadHelper;
use App\Services\RedeNeuralService;
ini_set("precision", 4);
ini_set('serialize_precision', 4);
set_time_limit(120);

require "vendor/autoload.php";

$fileUpload = FileUploadHelper::salvarUpload($_FILES);
if ($fileUpload['success']) {
    $config = $_POST;
    $retorno = RedeNeuralService::executar($fileUpload['file_dir'], $config);
    echo json_encode([
        'success' => true,
        'data' => $retorno
    ]);
} else
    echo json_encode([
        'success' => false,
        'error' => 'Erro no upload'
    ]);