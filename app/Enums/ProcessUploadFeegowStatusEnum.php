<?php

namespace App\Enums;

enum ProcessUploadFeegowStatusEnum: int
{
    case SUCCESS = 1;
    case FAILED = 2;

    public function getName(): string
    {
        return match ($this) {
            self::SUCCESS => "Successo",
            self::FAILED => "Falha",
            default => "Status não encontrado"
        };
    }
}
