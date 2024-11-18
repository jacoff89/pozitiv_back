<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class JsonResponseHelper
{
    public static function success($data = null, $message = null, $statusCode = 200): JsonResponse
    {
        $responseArr = [
            'success' => true,
            'data' => $data,
        ];

        if ($message) $responseArr['message'] = $message;
        return response()->json($responseArr, $statusCode);
    }

    public static function error($message, $statusCode = 400, $errors = null): JsonResponse
    {
        $responseArr = [
            'success' => false,
            'message' => $message,
        ];

        if ($message) $responseArr['errors'] = $errors;
        return response()->json($responseArr, $statusCode);
    }

    public static function validationError($errors): JsonResponse
    {
        return self::error("Ошибка валидации", 422, $errors);
    }
}
