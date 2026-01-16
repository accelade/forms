<?php

declare(strict_types=1);

namespace Accelade\Forms\Http\Middleware;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\Response;

class ValidateSelectToken
{
    /**
     * Handle an incoming request.
     *
     * All select configuration (model, attributes, etc.) is encrypted in the token
     * and NOT exposed in the URL for maximum security.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('X-Select-Token') ?? $request->input('_token');

        if (! $token) {
            return response()->json([
                'error' => 'Missing security token',
                'data' => [],
                'hasMore' => false,
            ], 403);
        }

        try {
            $payload = Crypt::decryptString($token);
            $data = json_decode($payload, true);

            if (! $data || ! isset($data['model']) || ! isset($data['timestamp'])) {
                throw new \InvalidArgumentException('Invalid token payload');
            }

            // Check if token is expired (valid for 24 hours by default)
            $tokenAge = time() - $data['timestamp'];
            $maxAge = config('forms.select_token_lifetime', 86400); // 24 hours default

            if ($tokenAge > $maxAge) {
                return response()->json([
                    'error' => 'Token expired',
                    'data' => [],
                    'hasMore' => false,
                ], 403);
            }

            // Validate model class exists and is an Eloquent model
            $modelClass = $data['model'];

            if (! class_exists($modelClass)) {
                return response()->json([
                    'error' => 'Invalid model',
                    'data' => [],
                    'hasMore' => false,
                ], 403);
            }

            if (! is_subclass_of($modelClass, Model::class)) {
                return response()->json([
                    'error' => 'Invalid model type',
                    'data' => [],
                    'hasMore' => false,
                ], 403);
            }

            // Store decoded payload for controller use
            // This contains: model, label_attribute, value_attribute, search_columns, per_page
            $request->merge(['_select_token_payload' => $data]);

            return $next($request);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Invalid security token',
                'data' => [],
                'hasMore' => false,
            ], 403);
        }
    }
}
