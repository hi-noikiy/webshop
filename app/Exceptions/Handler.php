<?php namespace App\Exceptions;

use Exception, Redirect, App;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler {

	/**
	 * A list of the exception types that should not be reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		'Symfony\Component\HttpKernel\Exception\HttpException'
	];

	/**
	 * Report or log an exception.
	 *
	 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
	 *
	 * @param  \Exception  $e
	 * @return void
	 */
	public function report(Exception $e)
	{
		return parent::report($e);
	}

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Exception $e
	 * @return \Illuminate\Http\Response
	 */
	public function render($request, Exception $e)
	{
		if (get_class($e) === "Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException")
			return App::abort(404);
		elseif (get_class($e) === "Illuminate\Session\TokenMismatchException")
			return Redirect::to('/')->withErrors("Uw sessie is verlopen, log opnieuw in en probeer het opnieuw");
		elseif ($this->isHttpException($e))
			return $this->renderHttpException($e);
		else
			return parent::render($request, $e);
	}

}
