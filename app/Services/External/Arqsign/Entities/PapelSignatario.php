<?php

namespace App\Services\External\Arqsign\Entities;

class PapelSignatario
{
    public ?array $pessoaFisica;
    public ?array $pessoaJuridica;

    public function __construct(array $data)
    {
        $this->pessoaFisica = data_get($data, 'PessoaFisica', []);
        $this->pessoaJuridica = data_get($data, 'PessoaJuridica', []);
    }
}
