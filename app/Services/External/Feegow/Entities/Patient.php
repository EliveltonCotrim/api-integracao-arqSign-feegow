<?php

namespace App\Services\External\Feegow\Entities;

class Patient
{
    public bool $empty = false;
    public ?int $id;
    public ?string $name;
    public ?string $nascimento;
    public ?string $cpf;

    public function __construct(array $data)
    {
        if (empty($data)) {
            $this->empty = true;
        }

        $this->id = data_get($data, 'id');
        $this->name = data_get($data, 'nome');
        $this->nascimento = data_get($data, 'nascimento');

        $this->nascimento = data_get($data, 'nascimento');

        $this->cpf = data_get($data, 'documentos.cpf');
    }

    public function getNascimentoAsDateTime()
    {

    }

}
