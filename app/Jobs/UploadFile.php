<?php

namespace App\Jobs;

use App\Enums\ProcessUploadFeegowStatusEnum;
use App\Exceptions\FeegowException;
use App\Models\FeegowUploadProcess;
use App\Models\UploadFilesHistory;
use App\Services\External\Feegow\Facades\FeegowApi;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class UploadFile implements ShouldQueue
{
    use Queueable;

    public array $dataUploadFeegow;
    public UploadFilesHistory $processo;

    /**
     * Create a new job instance.
     *
     * @param array $dataUploadFeegow
     */
    public function __construct(array $dataUploadFeegow, UploadFilesHistory $processo)
    {
        $this->dataUploadFeegow = $dataUploadFeegow;
        $this->processo = $processo;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $response = FeegowApi::patient()->uploadFile($this->dataUploadFeegow);

        $errorMessage = $this->getMessages($response, $this->dataUploadFeegow);

        throw_if(
            (isset($response['success']) && !$response['success']) || isset($response['base64_file']),
            FeegowException::class,
            $errorMessage,
        );

        $this->dataUploadFeegow['upload_status'] = ProcessUploadFeegowStatusEnum::SUCCESS->value;

        $this->processo->feegowUploadProcess()->create($this->dataUploadFeegow);

        logger()->channel('single')->info('Upload do arquivo concluído com sucesso.', ['paciente_id' => $this->dataUploadFeegow['paciente_id']]);

    }

    /**
     * Extract error messages from response.
     *
     * @param array $response
     * @param array $dataUploadFeegow
     * @return string
     */
    protected function getMessages(array $response, array $dataUploadFeegow): string
    {
        $messages = [];
        $errorMessage = 'Erro ao tentar fazer o upload do arquivo para o paciente: ' . $dataUploadFeegow['paciente_id'];

        if (!isset($response['success'])) {

            foreach ($response as $field => $errors) {
                if (is_array($errors)) {
                    foreach ($errors as $error) {
                        $messages[] = "{$field}: {$error}";
                    }
                }
            }

            $errorMessage = !empty($messages) ? implode('; ', $messages) : $errorMessage;
        }

        return $errorMessage;
    }

    public function failed(FeegowException $feegowException): void
    {
        // 'Erro ao tentar fazer o upload do arquivo do paciente com cpf: ' . $this->data['cpf'],
        logger()->error($feegowException->getMessage());

    }

    public function tries(): int
    {
        return 3;
    }

    /**
     * Calculate the number of seconds to wait before retrying the job.
     */
    public function backoff(): int
    {
        return 5;
    }
}
