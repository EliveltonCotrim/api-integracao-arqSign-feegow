<?php

namespace App\Enums;

enum StatusSignatureEnum: int
{
    case SUCCESS = 1;

    public static function parse($status)
    {
        switch ($status) {
            case 'ProcessoConcluido':
                return self::SUCCESS;
            default:
                return null;
        }
    }
}
