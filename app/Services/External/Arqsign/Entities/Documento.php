<?php

namespace App\Services\External\Arqsign\Entities;

class Documento
{
    public string $id;
    public int $ordemDocumento;
    public string $oomeDocumento;
    public string $base64Documento;
    public ?string $linkDocumentosCompartilhados;

    /** @var RegistroAssinatura[] */
    public array $registroAssinaturas;

    public function __construct(array $data)
    {
        $this->id = data_get($data, 'Id');
        $this->ordemDocumento = data_get($data, 'OrdemDocumento');
        $this->nomeDocumento = data_get($data, 'NomeDocumento');
        $this->base64Documento = data_get($data, 'Base64Documento');
        $this->linkDocumentosCompartilhados = data_get($data, 'LinkDocumentosCompartilhados');

        // $this->registroAssinaturas = array_map(
        //     fn($registro) => new RegistroAssinatura($registro),
        //     data_get($data, 'RegistroAssinaturas', [])
        // );
    }
}
