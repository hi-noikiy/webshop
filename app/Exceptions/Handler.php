<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use GrahamCampbell\Exceptions\NewExceptionHandler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
        \App\Exceptions\ProductNotFoundException::class,
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
        try {
            // Create a sentry variable
            $sentry = app('sentry');

            // Add the user login if someone is logged in
            if (auth()->check()) {
                $sentry->user_context([
                    'id'        => auth()->user()->company_id,
                    'username'  => auth()->user()->username,
                ]);
            }

            $sentry->captureException($exception);
        } catch (\ReflectionException $e) {
            \Log::error('An error occurred while trying to capture an exception with Sentry: '.$e->getMessage());
        }


        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if (
            $exception instanceof AuthenticationException ||
            $exception instanceof TokenMismatchException
        ) {
            return $this->unauthenticated($request, $exception);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return redirect()->back();
        }

        return parent::render($request, $exception);
    }
    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()
            ->guest(route('auth.login'))
            ->withErrors('U moet ingelogd zijn om deze url te benaderen. Als u ingelogd was is uw sessie mogelijk verlopen.');
    }
}
