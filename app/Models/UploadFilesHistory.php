<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class UploadFilesHistory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'arqSign_idProcesso',
        'arqSign_idConta',
        'arqSign_idWebhook',
        'arqSign_dataHoraAtualProcesso',
        'arqSign_nomeProcesso',
        'arqSign_idPasta',
        'arqSign_caminhoDaPasta',
        'arqSign_idResponsavel',
        'arqSign_NomeResponsavel',
        'arqSign_status',
        'arqSign_dataConclusao',
        'statusProcesso',
        'signatarios',
        'documentos'
    ];

    protected $casts = [
        'signatarios'=> 'array',
        'documentos'=> 'array'
    ];
}
