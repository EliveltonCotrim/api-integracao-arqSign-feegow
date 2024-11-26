<?php

namespace App\Services\External\Arqsign\Entities;

class DadosCertificado
{
    public ?string $nome;
    public ?string $emissor;
    public ?string $validadeInicio;
    public ?string $validadeFim;

    public function __construct(array $data)
    {
        $this->nome = data_get($data, 'Nome');
        $this->emissor = data_get($data, 'Emissor');
        $this->validadeInicio = data_get($data, 'ValidadeInicio');
        $this->validadeFim = data_get($data, 'ValidadeFim');
    }

    private function parseDate(?string $date): ?\DateTime
    {
        return $date ? new \DateTime($date) : null;
    }
}
