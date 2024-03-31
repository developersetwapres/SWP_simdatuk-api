<?php

namespace App\Exceptions;

use App\Helpers\Responser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use Responser;

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
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        if (config('app.debug')) {
            return parent::render($request, $e);
        }

        if ($request->wantsJson()) {
            if ($e instanceof QueryException) {
                return $this->response(500, 'Mohon maaf, fitur dalam kendala harap hubungi Tim IT!');
            } else if ($e instanceof ValidationException) {
                $messages = $e->validator->errors()->all();
                return $this->response(422, $messages[0], $e->validator->errors());
            } else if ($e instanceof AuthorizationException) {
                return $this->response(401, 'Anda tidak memiliki hak akses!');
            } else if ($e instanceof AuthenticationException) {
                return $this->response(401, 'Anda harus login terlebih dahulu!');
            } else if ($e instanceof NotFoundHttpException) {
                return $this->response(404, 'Endpoint tidak ditemukan!');
            } else if ($e instanceof MethodNotAllowedHttpException) {
                return $this->response(405, 'Method yang digunakan salah!');
            }
        }

        return parent::render($request, $e);
    }
}
