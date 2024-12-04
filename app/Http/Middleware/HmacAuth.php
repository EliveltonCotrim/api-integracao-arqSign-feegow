<?php

namespace App\Http\Middleware;

use App\Exceptions\FeegowException;
use App\Exceptions\HmacException;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class HmacAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Hmac wehbook
        $secret = trim(config('arqsign.keys.hmac_webhook'));
        $payload = $request->getContent();

        // Hmac verify
        $verify = trim($request->header('hmac'));
        $hexHash = hash_hmac('sha256', $payload, $secret, true);

        // Calcular HMAC e codificar como Base64
        $base64Hash = base64_encode($hexHash);

        if (!hash_equals($verify, $base64Hash)) {
            Log::warning('Tentativa de acesso com HMAC inválido.', ['ip' => $request->ip()]);

            throw new HmacException('Acesso não autorizado.');
        }

        return $next($request);
    }
}
