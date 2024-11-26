<?php

namespace App\Services\External\Arqsign\Entities;

class DadosPessoaFisica
{
    public ?string $nomePessoaFisica;
    public ?string $tipoDocumentoPessoaFisica;
    public string $numeroDocumentoPessoaFisica;

    public function __construct(array $data)
    {
        $this->nomePessoaFisica = data_get($data, 'NomePessoaFisica');
        $this->tipoDocumentoPessoaFisica = data_get($data, 'TipoDocumentoPessoaFisica');
        $this->numeroDocumentoPessoaFisica = data_get($data, 'NumeroDocumentoPessoaFisica');
    }
}
