<?php

namespace App\Exceptions;

use App\Traits\ApiResponserTrait;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

/**
 * Class Handler
 *
 * Centralized exception handling with API response standardization.
 *
 * @package App\Exceptions
 */
class Handler extends ExceptionHandler
{
    use ApiResponserTrait;

    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
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
        $this->renderable(function (Throwable $e, Request $request) {
            if ($request->is('api/*') || $request->wantsJson()) {
                return $this->handleApiException($e, $request);
            }
        });
    }

    /**
     * Determine the type of exception and return the appropriate JSON response.
     *
     * @param Throwable $e
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function handleApiException(Throwable $e, Request $request)
    {
        if ($e instanceof ValidationException) {
            return $this->failResponse(
                $e->errors(),
                ResponseAlias::HTTP_UNPROCESSABLE_ENTITY,
                $e->getMessage()
            );
        }

        if ($e instanceof AuthenticationException) {
            return $this->failResponse(
                [],
                ResponseAlias::HTTP_UNAUTHORIZED,
                'Unauthenticated.'
            );
        }

        if ($e instanceof NotFoundHttpException) {
            return $this->failResponse(
                [],
                ResponseAlias::HTTP_NOT_FOUND,
                'Resource or Endpoint Not Found.'
            );
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return $this->failResponse(
                [],
                ResponseAlias::HTTP_METHOD_NOT_ALLOWED,
                'Method Not Allowed.'
            );
        }

        $statusCode = method_exists($e, 'getStatusCode')
            ? $e->getStatusCode()
            : ResponseAlias::HTTP_INTERNAL_SERVER_ERROR;

        return $this->errorResponse(
            $e,
            'Internal Server Error',
            $statusCode,
            $request
        );
    }
}
