<?php

namespace App\Helpers;

class ArrayHelper
{
    public static function findPares(array $arrayOfObjects,$value): array
    {
        return array_filter($arrayOfObjects, function ($obj) use ($value) {
            return $obj->neuronio->id == $value;
        });
    }

}