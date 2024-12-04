<?php

namespace App\Services\External\Arqsign\Entities;

class WebhookNotification
{
    public ?string $evento;
    public string $idProcesso;
    public string $idConta;
    public string $idWebhook;
    public string $nomeProcesso;
    public ?string $idPasta;
    public ?string $caminhoDaPasta;
    public ?string $idResponsavel;
    public ?string $nomeResponsavel;
    public ?string $status;
    public string $dataHoraAtual;
    public ?string $dataCadastro;
    public ?string $dataEnvio;
    public ?string $dataConclusao;
    public ?string $dataReenvio;
    public ?string $dataExpiracao;
    public ?int $expiracaoDias;
    public array $signatarios;
    public array $documentos;

    public function __construct(array $data)
    {
        $this->evento = data_get($data, 'Evento');
        $this->idProcesso = data_get($data, 'IdProcesso');
        $this->idConta = data_get($data, 'IdConta');
        $this->idWebhook = data_get($data, 'IdWebhook');
        $this->nomeProcesso = data_get($data, 'NomeProcesso');
        $this->idPasta = data_get($data, 'IdPasta');
        $this->caminhoDaPasta = data_get($data, 'CaminhoDaPasta');
        $this->idResponsavel = data_get($data, 'IdResponsavel');
        $this->nomeResponsavel = data_get($data, 'NomeResponsavel');
        $this->status = data_get($data, 'Status');
        $this->dataHoraAtual = data_get($data, 'DataHoraAtual');
        $this->dataCadastro = data_get($data, 'DataCadastro');
        $this->dataEnvio = data_get($data, 'DataEnvio');
        $this->dataConclusao = data_get($data, 'DataConclusao');
        $this->dataReenvio = data_get($data, 'DataReenvio');
        $this->dataExpiracao = data_get($data, 'DataExpiracao');
        $this->expiracaoDias = data_get($data, 'ExpiracaoDias');
        $this->signatarios = array_map(
            fn($signatario) => new Signatario($signatario),
            data_get($data, 'Signatarios', [])
        );
        $this->documentos = array_map(fn($documento) => new Documento($documento), data_get($data, 'Documentos', []));

        // $this->Signatarios = data_get($data, 'Signatarios', []);
        // $this->Documentos = data_get($data, 'Documentos', []);
    }
}
