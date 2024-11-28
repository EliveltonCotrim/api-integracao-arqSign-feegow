<?php

namespace App\Http\Controllers;

use App\Exceptions\ArqSignWebhookException;
use App\Http\Requests\ArqSignWebhookRequest;
use App\Services\ArqSignService;
use Illuminate\Http\JsonResponse;

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
    }
}
