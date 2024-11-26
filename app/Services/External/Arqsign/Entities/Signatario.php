<?php

namespace App\Services\External\Arqsign\Entities;

class Signatario
{
    public int $ordemAssinatura;
    public string $id;
    public string $nome;
    public ?string $email;
    public ?string $telefone;

    public ?PapelSignatario $papelSignatario;
    public ?DadosAssinatura $dadosAssinatura;

    public ?string $assinaturaRecusada;
    public ?string $motivoRecusa;

    public function __construct(array $data)
    {
        $this->ordemAssinatura = data_get($data, 'OrdemAssinatura');
        $this->id = data_get($data, 'Id');
        $this->nome = data_get($data, 'Nome');
        $this->email = data_get($data, 'Email');
        $this->telefone = data_get($data, 'Telefone');

        $this->papelSignatario = isset($data['PapelSignatario'])
            ? new PapelSignatario($data['PapelSignatario'])
            : null;

        $this->dadosAssinatura = isset($data['DadosAssinatura'])
            ? new DadosAssinatura($data['DadosAssinatura'])
            : null;

        $this->assinaturaRecusada = data_get($data, 'AssinaturaRecusada');
        $this->motivoRecusa = data_get($data, 'MotivoRecusa');
    }
}
