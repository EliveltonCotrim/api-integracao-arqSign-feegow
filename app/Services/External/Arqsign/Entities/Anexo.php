<?php

namespace App\Services\External\Arqsign\Entities;

class Anexo
{
    public ?string $id;
    public ?string $anexoDocumentoNome;
    public ?string $linkParaBaixarDocumento;

    public function __construct(array $data)
    {
        $this->id = data_get($data, 'Id');
        $this->anexoDocumentoNome = data_get($data, 'AnexoDocumentoNome');
        $this->linkParaBaixarDocumento = data_get($data, 'LinkParaBaixarDocumento');
    }
}
