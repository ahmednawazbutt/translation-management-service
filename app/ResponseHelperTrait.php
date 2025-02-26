<?php

namespace App;

trait ResponseHelperTrait
{
    public function successResponse($data, $message = 'Success', $statusCode = 200)
    {
        return response()->json([
           'status' =>'success',
           'message' => $message,
           'data' => $data
        ], $statusCode);
    }

    public function errorResponse($message = 'Error', $statusCode = 500, $data = [])
    {
        return response()->json([
           'status' => 'error',
           'message' => $message,
           'data' => $data
        ], $statusCode);
    }

    public function notFoundResponse($message = 'Not Found')
    {
        return $this->errorResponse($message, 404);
    }

    public function unauthorizedResponse($message = 'Unauthorized')
    {
        return $this->errorResponse($message, 401);
    }

    public function forbiddenResponse($message = 'Forbidden')
    {
        return $this->errorResponse($message, 403);
    }
}
