<?php

namespace App\Helpers;

class FileUploadHelper
{
    public static function salvarUpload(array $file): array
    {
        $uploaddir = './tmp/';
        if(!is_dir($uploaddir)){
            mkdir('tmp');
        }
        $uploadfile = $uploaddir . basename($file['inputFile']['name']);
        try{
            move_uploaded_file($_FILES['inputFile']['tmp_name'], $uploadfile);
            return [
                'success' => true,
                'file_dir' => $uploadfile
            ];
        }catch (\Exception $ex){
            return [
                'success' => false
            ];
        }
    }
}