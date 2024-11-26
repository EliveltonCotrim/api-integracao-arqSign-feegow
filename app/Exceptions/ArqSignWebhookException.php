<?php

namespace App\Exceptions;

use Exception;

use Illuminate\Http\Response;


class ArqSignWebhookException extends Exception
{
    protected $message = "Erro ao processar webhook da ArqSign";
    protected $code = Response::HTTP_BAD_REQUEST;

    public function render()
    {
        response()->json(
            [
                "message" => $this->message,

            ],
            $this->code
        );
    }
}
