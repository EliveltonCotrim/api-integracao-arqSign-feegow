<?php

namespace App\Http\Controllers;

use App\Exceptions\FeegowException;
use App\Http\Requests\ArqSignWebhookRequest;
use App\Services\IntegracaoArqSignFeegowService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class IntegracaoArqSignFeegowController extends Controller
{
    private IntegracaoArqSignFeegowService $feegowService;

    public function __construct(){
        $this->feegowService = new IntegracaoArqSignFeegowService();
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

        } catch (FeegowException $e) {

            Log::error($e->getMessage());

            throw $e;

        } catch (\Throwable $th) {

            Log::error($e->getMessage());

            throw $th;
        }
    }
}
