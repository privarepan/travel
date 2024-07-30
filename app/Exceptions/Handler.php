<?php

namespace App\Exceptions;

use App\Traits\ResponseTrait;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ResponseTrait;
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
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (ValidationException $e,Request $request) {
            if ($request->expectsJson()) {
                return $this->error($e->getMessage(),$e->status,$e->errors());
            }
        });

        $this->renderable(function (AuthenticationException $e, Request $request) {
            if ($request->expectsJson()) {
                return $this->error(401,[],'请登录后进行该操作');
            }
        });

        $this->renderable(function (Throwable $e, Request $request) {
            if ($request->expectsJson()) {
                return $this->error($e->getMessage());
            }
        });
    }
}
