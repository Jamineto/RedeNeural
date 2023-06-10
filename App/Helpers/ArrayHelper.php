<?php

namespace App\Helpers;

class ArrayHelper
{
    public static function findPares(array $arrayOfObjects, $value, $type = 1): array
    {
        return array_filter($arrayOfObjects, function ($obj) use ($value,$type) {
            if($type === 1){
                return $obj->neuronio->id == $value;
            }
            else{
                return $obj->saida->id == $value;
            }
        });
    }

}