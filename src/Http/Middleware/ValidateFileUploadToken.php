<?php

declare(strict_types=1);

namespace Accelade\Forms\Http\Middleware;

use Accelade\Forms\Support\FileUploadToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to validate file upload security tokens.
 *
 * All file upload configuration is encrypted in the token and validated here
 * before any upload operation is allowed.
 */
class ValidateFileUploadToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('X-Upload-Token') ?? $request->input('_token');

        if (! $token) {
            return response()->json([
                'error' => 'Missing upload security token',
                'success' => false,
            ], 403);
        }

        $payload = FileUploadToken::validate($token);

        if ($payload === null) {
            return response()->json([
                'error' => 'Invalid or expired upload token',
                'success' => false,
            ], 403);
        }

        // Store decoded payload for controller use
        $request->merge(['_upload_token_payload' => $payload]);

        return $next($request);
    }
}
