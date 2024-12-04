<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Documento extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'upload_files_history_id',
        'id_documento',
        'ordemDocumento',
        'nomeDocumento',
    ];

    public function uploadFileHitory(): BelongsTo
    {
        return $this->belongsTo(UploadFilesHistory::class);
    }
}
