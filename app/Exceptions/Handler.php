<?php

namespace App\Exceptions;

use App\Helpers\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var list<string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (Throwable $e, $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                if ($e instanceof ValidationException) {
                    return ApiResponse::error('Validation error.', Response::HTTP_UNPROCESSABLE_ENTITY, $e->errors());
                }

                if ($e instanceof AuthenticationException) {
                    return ApiResponse::error('Unauthenticated.', Response::HTTP_UNAUTHORIZED);
                }

                if ($e instanceof NotFoundHttpException) {
                    return ApiResponse::error('Resource not found.', Response::HTTP_NOT_FOUND);
                }

                return ApiResponse::error($e->getMessage() ?: 'Server error occurred.', Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        });
    }
}
