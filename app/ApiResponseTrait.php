<?php

namespace App;

trait ApiResponseTrait
{
    public function successResponse($data, $message = null, $statusCode = 200) {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    public function errorResponse($message, $statusCode = 203) {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => null,
        ], $statusCode);
    }
}