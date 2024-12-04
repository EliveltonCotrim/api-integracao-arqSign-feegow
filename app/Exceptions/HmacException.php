<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;


class HmacException extends Exception
{
    protected $message = "Tentativa de acesso com HMAC inválido.";
    protected $code = Response::HTTP_FORBIDDEN;

    public function report()
    {
        Log::warning($this);
    }

    public function render()
    {
        return response()->json(
            ["message" => $this->message],
            $this->code
        );
    }
}
