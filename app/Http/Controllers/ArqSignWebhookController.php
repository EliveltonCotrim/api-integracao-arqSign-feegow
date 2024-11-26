<?php

namespace App\Http\Controllers;

use App\Enums\StatusSignatureEnum;
use App\Exceptions\ArqSignWebhookException;
use App\Http\Requests\ArqSignWebhookRequest;
use App\Jobs\UploadFile;
use App\Services\ArqSignService;
use App\Services\External\Arqsign\Entities\WebhookNotification;
use App\Services\External\Feegow\FeegowService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArqSignWebhookController extends Controller
{
    private ArqSignService $feegowService;

    public function __construct(){
        $this->feegowService = new ArqSignService();
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(ArqSignWebhookRequest $request)
    {
        $data = $request->validated();

        try {
            $this->feegowService->dataProcessing($data);

            return response()->json(status: JsonResponse::HTTP_OK);

        } catch (ArqSignWebhookException $e) {
            throw $e;

        } catch (\Throwable $th) {
            throw $th;
        }

        // if (StatusSignatureEnum::parse($request->Evento) === StatusSignatureEnum::SUCCESS) {

        //     $data = new WebhookNotification($request->all());

        //     $feegowService = new FeegowService();
        //     $patients = [];
        //     $reponses = [];

        //     foreach ($request->Signatarios as $key => $signatario) {
        //         $patient_cpf = $signatario['DadosAssinatura']['DadosPessoaFisica']['NumeroDocumentoPessoaFisica'];
        //         $patients[$key] = $feegowService->patient()->searchPatient($patient_cpf);
        //     }

        //     // Validar se encontrou todos sinatarios, se não encontrou, retornar erro
        //     throw_if(
        //         in_array(null, $patients, true),
        //         ArqSignWebhookException::class,
        //         'Paciente não encontrado.'
        //     );

        //     foreach ($patients as $key => $patient) {
        //         $dataUploadFeegow = [
        //             'paciente_id' => $patient->id,
        //             'cpf' => $patient->cpf, // verificar se é pessoa fisica (DadosPessoaFisica) ou juridica (DadosPessoaJuridica)
        //             'nascimento' => $patient->nascimento,
        //         ];

        //         foreach ($data->documentos as $key => $documento) {
        //             $dataUploadFeegow['base64_file'] = $documento->base64Documento;
        //             $dataUploadFeegow['arquivo_descricao'] = $documento->nomeDocumento;

        //             $feegowService->patient()->uploadFile($dataUploadFeegow);
        //             // Verificar a necessidade de implementar fila.
        //         }
        //     }

        // }

        // return response()->json(status: JsonResponse::HTTP_OK);
    }
}
