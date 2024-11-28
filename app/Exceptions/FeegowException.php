<?php

namespace App\Exceptions;

use Exception;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class FeegowException extends Exception
{
    protected $message = "Erro ao processar webhook da ArqSign";
    protected $code = Response::HTTP_BAD_REQUEST;

    public function report()
    {
        Log::critical($this);
    }

    public function render()
    {
        return response()->json(
            [
                "message" => $this->message,

            ],
            $this->code
        );
    }
}
