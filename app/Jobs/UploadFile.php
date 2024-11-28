<?php

namespace App\Jobs;

use App\Services\External\Feegow\Facades\FeegowApi;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class UploadFile implements ShouldQueue
{
    use Queueable;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        FeegowApi::patient()->uploadFile($this->data);

        logger()->info("File uploaded to Feegow", $this->data['cpf']);
    }

    public function failed(\Throwable $exception): void
    {
        // 'Erro ao tentar fazer o upload do arquivo do paciente com cpf: ' . $this->data['cpf'],
        logger()->error( $exception->getMessage());
    }
}
