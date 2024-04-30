<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use App\Facades\Response;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use App\Models\User;
use App\Notifications\ExceptionNotification;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
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

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof BadRequestException) {
            return $this->renderError($exception, 400);
        }

        if ($exception instanceof AuthenticationException) {
            return $this->renderError($exception, 401);
        }

        if ($exception instanceof NotFoundHttpException) {
            return $this->renderError($exception, 404);
        }

        if ($exception instanceof NotFoundHttpException) {
            return $this->renderError($exception, 404);
        }

        if ($exception instanceof ModelNotFoundException) {
            return $this->renderError($exception, 404);
        }

        if ($exception instanceof UnauthorizedException) {
            return $this->renderError($exception, 401);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->renderError($exception, 403);
        }

        if ($exception instanceof ValidationException) {
            return $this->renderError($exception, 422, null,  $exception->errors());
        }

        if ($exception instanceof ThrottleRequestsException) {
            return $this->renderError($exception, 429);
        }

        return $this->renderError(
            $exception,
            500,
            null,
            null,
            app()->isProduction() ? [] : $exception->getTrace()
        );
    }

    private function renderError(Throwable $e, $status, ?string $message = null, $errors = [], $data = [])
    {
        $ExceptionNotification = new ExceptionNotification(
            $e->getMessage(),
            json_encode($e->getTrace(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );

        User::whereEmail(config('technopay.ADMIN_EMAIL'))->first()
            ->notify($ExceptionNotification);

        return Response::status($status)
            ->message($message ? $message : $e->getMessage())
            ->data($data)
            ->errors($errors);
    }
}
