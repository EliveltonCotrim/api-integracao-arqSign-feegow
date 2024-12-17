<?php

namespace App\Models;

use App\Enums\ProcessUploadFeegowStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeegowUploadProcess extends Model
{
    protected $fillable = [
        'upload_files_history_id',
        'paciente_id',
        'cpf',
        'nascimento',
        'arquivo_descricao',
        'upload_status',
    ];

    protected $casts = [
        'upload_status' => ProcessUploadFeegowStatusEnum::class,
    ];

    public function uploadFilesHistory(): BelongsTo
    {
        return $this->belongsTo(UploadFilesHistory::class);
    }
}
