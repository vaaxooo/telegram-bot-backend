<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        });

        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'code' => 404,
                    'message' => 'Method Not Found',
                    'status' => 'error'
                ]);
            }
        });
    }


    /**
     * If the user is not authenticated, return a JSON response with a 401 status code
     * 
     * @param request The incoming request.
     * @param AuthenticationException exception The exception that was thrown.
     * 
     * @return A JSON response with a 401 status code.
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->json([
            'code' => 401,
            'message' => 'Unauthenticated',
            'status' => 'error'
        ]);
    }

    /**
     * If the request is an AJAX request, return a JSON response with the error code, message, and
     * status
     * 
     * @param request The current request.
     * @param Throwable e The exception that was thrown
     * 
     * @return A JSON response with a 500 status code.
     */
    public function render($request, Throwable $e)
    {
        return response()->json([
            'code' => 500,
            'message' => $e->getMessage(),
            'status' => 'error'
        ]);
    }
}
