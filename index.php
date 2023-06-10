<?php

use App\Helpers\FileUploadHelper;
use App\Services\RedeNeuralService;
ini_set("precision", 10);
ini_set('serialize_precision', 10);

require "vendor/autoload.php";

$fileUpload = FileUploadHelper::salvarUpload($_FILES);
if ($fileUpload['success']) {
    $config = $_POST;
    RedeNeuralService::executar($fileUpload['file_dir'], $config);
} else
    return json_encode([
        'success' => false,
        'error' => 'Erro no upload'
    ]);