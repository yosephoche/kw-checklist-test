<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Http\Response;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function render($request, Exception $exception)
    {
        if (env('APP_ENV') != 'testing' && env('APP_DEBUG') == true) {
            return parent::render($request, $exception);
        }
    
        $status = Response::HTTP_INTERNAL_SERVER_ERROR;
    
        if ($exception instanceof HttpResponseException) {
            $status    = Response::HTTP_INTERNAL_SERVER_ERROR;
        } elseif ($exception instanceof MethodNotAllowedHttpException) {
            $status    = Response::HTTP_METHOD_NOT_ALLOWED;
            $exception = new MethodNotAllowedHttpException([], 'Method Not Allowed', $exception);
        } elseif ($exception instanceof NotFoundHttpException) {
            $status    = Response::HTTP_NOT_FOUND;
            $exception = new NotFoundHttpException('Not Found', $exception);
        } elseif ($exception instanceof AuthorizationException) {
            $status    = Response::HTTP_FORBIDDEN;
            $exception = new AuthorizationException('Forbidden', $status);
        } elseif ($exception instanceof \Dotenv\Exception\ValidationException && $exception->getResponse()) {
            $status    = Response::HTTP_BAD_REQUEST;
            $exception = new \Dotenv\Exception\ValidationException('Bad Request', $status, $exception);
        } elseif ($exception) {
            $exception = new HttpException($status, 'Server Error');
        }
    
        return response()->json([
            'status'  => $status,
            'message' => $exception->getMessage()
        ], $status);
    }
}
