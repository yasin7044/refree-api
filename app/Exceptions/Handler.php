<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception) {
        $responseBody = [
            'message' => null,
            'errors'  => []
        ];
        if($exception instanceof AuthenticationException) {
            $responseBody['message'] = $exception->getMessage();
            return response()->json($responseBody, Response::HTTP_UNAUTHORIZED);
        } else if($exception instanceof AuthorizationException) {
            $responseBody['message'] = $exception->getMessage();
            return response()->json($responseBody, Response::HTTP_FORBIDDEN);
        } else if($exception instanceof ValidationException) {
            $responseBody['message'] = 'Validation Error';
            $responseBody['errors'] = $exception->errors();
            return response()->json($responseBody, Response::HTTP_UNPROCESSABLE_ENTITY);
        } else if($exception instanceof NotFoundHttpException) {
            $responseBody['message'] = 'The requested url does not exist.';
            return response()->json($responseBody, Response::HTTP_NOT_FOUND);
        } else if($exception instanceof MethodNotAllowedHttpException) {
            $responseBody['message'] = 'The requested method is not allowed.';
            return response()->json($responseBody, Response::HTTP_METHOD_NOT_ALLOWED);
        } else if($exception instanceof HttpException) {
            $responseBody['message'] = $exception->getMessage();
            return response()->json($responseBody, $exception->getStatusCode());
        } else {
            if(env('APP_ENV') == 'local' || env('APP_ENV') == 'development'){

                $responseBody['message'] =  $exception->getMessage();
                $responseBody['errors']  = $exception->getTrace();
            } else {
                $responseBody['message'] = $exception->getMessage();
            }

            return response()->json($responseBody, 400);
        }
    }
}
