<?php

namespace App\Services\External\Arqsign\Entities;

class DadosPessoaJuridica
{
    public ?string $nomeEmpresa;
    public ?string $tipoDocumentoEmpresa;
    public ?string $numeroDocumentoEmpresa;

    public function __construct(array $data)
    {
        $this->nomeEmpresa = data_get($data, 'NomeEmpresa');
        $this->tipoDocumentoEmpresa = data_get($data, 'TipoDocumentoEmpresa');
        $this->numeroDocumentoEmpresa = data_get($data, 'NumeroDocumentoEmpresa');
    }
}
