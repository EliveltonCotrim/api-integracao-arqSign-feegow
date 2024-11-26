<?php

namespace App\Services\External\Arqsign\Entities;

class RegistroAssinatura
{
    public string $nomeRegistro;
    public string $base64Registro;

    public function __construct(array $data)
    {
        $this->nomeRegistro = data_get($data, 'NomeRegistro');
        $this->base64Registro = data_get($data, 'Base64Registro');
    }
}
