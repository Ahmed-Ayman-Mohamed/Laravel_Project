<?php

namespace App;

trait ApiResponseTrait
{
    public function successResponse($data = [], $message = 'Success', $statusCode = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'status_code' => $statusCode,
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Send an error response.
     *
     * @param string $message
     * @param int $statusCode
     * @param array $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorResponse($message = 'Error', $statusCode = 400, $errors = [])
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'status_code' => $statusCode,
            'errors' => $errors,
        ], $statusCode);
    }

    /**
     * Send a validation error response.
     *
     * @param array $errors
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function validationErrorResponse($errors = [], $message = 'Validation Error')
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'status_code' => 422,
            'errors' => $errors,
        ], 422);
    }

    /**
     * Send an unauthorized response.
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function unauthorizedResponse($message = 'Unauthorized')
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'status_code' => 401,
        ], 401);
    }

    /**
     * Send a forbidden response.
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function forbiddenResponse($message = 'Forbidden')
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'status_code' => 403,
        ], 403);
    }

    /**
     * Send a not found response.
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function notFoundResponse($message = 'Resource Not Found')
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'status_code' => 404,
        ], 404);
    }
}
