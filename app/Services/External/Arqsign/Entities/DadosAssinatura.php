<?php

namespace App\Services\External\Arqsign\Entities;

class DadosAssinatura
{
    public ?string $tipoAssinatura;
    public ?string $dataAssinatura;
    public ?string $ip;
    public ?string $geoLocalizacao;

    public ?DadosCertificado $dadosCertificado;
    public ?DadosPessoaFisica $dadosPessoaFisica;
    public ?DadosPessoaJuridica $dadosPessoaJuridica;
    public array $anexos;

    public function __construct(array $data)
    {
        $this->tipoAssinatura = data_get($data, 'TipoAssinatura');
        $this->dataAssinatura = data_get($data, 'DataAssinatura');
        $this->ip = data_get($data, 'Ip');
        $this->geoLocalizacao = data_get($data, 'GeoLocalizacao');

        $this->dadosCertificado = isset($data['DadosCertificado'])
            ? new DadosCertificado($data['DadosCertificado'])
            : null;

        $this->dadosPessoaFisica = isset($data['DadosPessoaFisica'])
            ? new DadosPessoaFisica($data['DadosPessoaFisica'])
            : null;

        $this->dadosPessoaJuridica = isset($data['DadosPessoaJuridica'])
            ? new DadosPessoaJuridica($data['DadosPessoaJuridica'])
            : null;

        $this->anexos = array_map(
            fn($anexo) => new Anexo($anexo),
            data_get($data, 'Anexos', [])
        );
    }

    private function parseDate(?string $date): ?\DateTime
    {
        return $date ? new \DateTime($date) : null;
    }
}
