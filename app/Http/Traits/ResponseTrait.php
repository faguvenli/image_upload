<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response as HttpResponse;

trait ResponseTrait
{
    public static function successResponse(string $message, mixed $data = null, ...$args): JsonResponse
    {
        $response = [
            'flag' => 'success',
            'code' => HttpResponse::HTTP_OK,
            'message' => $message
        ];

        if($data != null) {
            $response['data'] = $data;
        }

        if($args) {
            foreach ($args as $key => $value) {
                $response[$key] = $value;
            }
        }

        return response()->json($response);
    }

    public static function errorResponse(string $message): JsonResponse
    {
        return response()->json([
            'flag' => 'error',
            'code' => HttpResponse::HTTP_UNPROCESSABLE_ENTITY,
            'message' => $message
        ]);
    }
}
