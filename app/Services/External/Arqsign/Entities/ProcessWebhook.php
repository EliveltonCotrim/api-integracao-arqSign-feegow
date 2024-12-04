<?php

namespace App\Services\External\Arqsign\Entities;

use DateTime;
use phpDocumentor\Reflection\Types\Integer;

class ProcessWebhook
{
    public $arqSign_idProcesso;
    public $arqSign_idConta;
    public $arqSign_idWebhook;
    public string $arqSign_dataHoraAtualProcesso;
    public string $arqSign_nomeProcesso;
    public $arqSign_idPasta;
    public $arqSign_caminhoDaPasta;
    public $arqSign_idResponsavel;
    public string $arqSign_NomeResponsavel;
    public string $arqSign_status;
    public string $arqSign_dataConclusao;

    public string $statusProcesso;

    public function __construct($data)
    {
        $this->arqSign_idProcesso = data_get($data, 'idProcesso');
        $this->arqSign_idConta = data_get($data, 'idConta');
        $this->arqSign_idWebhook = data_get($data, 'idWebhook');

        $this->arqSign_dataHoraAtualProcesso = data_get($data, 'dataHoraAtual');

        $this->arqSign_nomeProcesso = data_get($data, 'nomeProcesso');

        $this->arqSign_idPasta = data_get($data, 'idPasta');
        $this->arqSign_caminhoDaPasta = data_get($data, 'caminhoDaPasta');
        $this->arqSign_idResponsavel = data_get($data, 'idResponsavel');
        $this->arqSign_NomeResponsavel = data_get($data, 'nomeResponsavel');

        $this->arqSign_status = data_get($data, 'status');
        $this->statusProcesso = "Em processo";

        $this->arqSign_dataConclusao =  data_get($data, 'dataConclusao');

    }

    public function toArray(): array
    {
        return [
            'arqSign_idProcesso' => $this->arqSign_idProcesso,
            'arqSign_idConta' => $this->arqSign_idConta,
            'arqSign_idWebhook' => $this->arqSign_idWebhook,
            'arqSign_dataHoraAtualProcesso' => $this->arqSign_dataHoraAtualProcesso,
            'arqSign_nomeProcesso' => $this->arqSign_nomeProcesso,
            'arqSign_idPasta' => $this->arqSign_idPasta,
            'arqSign_caminhoDaPasta' => $this->arqSign_caminhoDaPasta,
            'arqSign_idResponsavel' => $this->arqSign_idResponsavel,
            'arqSign_NomeResponsavel' => $this->arqSign_NomeResponsavel,
            'arqSign_status' => $this->arqSign_status,
            'arqSign_dataConclusao' => $this->arqSign_dataConclusao,
        ];
    }
}
