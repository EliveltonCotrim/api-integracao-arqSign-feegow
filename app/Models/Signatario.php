<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Signatario extends Model
{
    Use SoftDeletes;

    protected $fillable = [
        'feegow_id_paciente',
        'feegow_nome_paciente',
        'feegow_nascimento',
        'feegow_cpf',
        'upload_files_history_id',
        'ordemAssinatura',
        'idSignatario',
        'nome',
        'email',
        'telefone',
        'pessoaFisica',
        'pessoaJuridica',
        'tipoAssinatura',
        'dataAssinatura',
        'geoLocalizacao',
        'ip',
        'tipoDocumentoPessoaFisica',
        'numeroDocumetoPessoaFisica',
    ];

    public function uploadFileHitory(): BelongsTo
    {
        return $this->belongsTo(UploadFilesHistory::class);
    }
}
