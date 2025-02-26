<?php

namespace App\Exceptions;

use App\ResponseHelperTrait;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    use ResponseHelperTrait;

    public function render($request, Throwable $exception)
    {
        // Debug: Log the exception type
        Log::info('Exception handled: ' . get_class($exception));

        // Check if the request expects JSON
        if ($request->expectsJson()) {
            // Handle NotFoundHttpException (e.g., invalid route parameter or missing resource)
            if ($exception instanceof NotFoundHttpException) {
                return $this->errorResponse('Resource not found', 404, [
                    'message' => 'The requested translation does not exist.',
                ]);
            }

            // Handle ModelNotFoundException (e.g., when a resource is not found)
            if ($exception instanceof ModelNotFoundException) {
                return $this->errorResponse('Resource not found', 404, [
                    'message' => 'The requested translation does not exist.',
                ]);
            }

            // Handle MethodNotAllowedHttpException (e.g., wrong HTTP method)
            if ($exception instanceof MethodNotAllowedHttpException) {
                return $this->errorResponse('Method not allowed', 405, [
                    'message' => 'The requested HTTP method is not supported for this route.',
                ]);
            }

            // Handle ValidationException (e.g., validation errors)
            if ($exception instanceof ValidationException) {
                return $this->errorResponse('Validation failed', 422, [
                    'message' => 'The given data was invalid.',
                    'errors' => $exception->errors(),
                ]);
            }
        }

        // Fall back to the default exception handler for non-JSON requests
        return parent::render($request, $exception);
    }
}
