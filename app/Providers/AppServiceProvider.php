<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Exceptions\LogException;

class AppServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
        \Event::listen('illuminate.log', function($level, $message) {
            switch ($level) {
                case 'emergency':
                case 'alert':
                    $level = 'fatal';
                    break;
                case 'critical':
                    $level = 'error';
                    break;
                case 'notice':
                    $level = 'info';
                    break;
            }

            // Create a sentry variable
            $sentry = app('sentry');

            // Add the user login if someone is logged in
            if (auth()->check()) {
                $sentry->user_context([
                    'id'        => auth()->user()->login,
                    'username'  => auth()->user()->company
                ]);
            }
            
            $sentry->captureException(new LogException($message), [
                'level' => $level
            ]);

        });
	}

	/**
	 * Register any application services.
	 *
	 * This service provider is a great spot to register your various container
	 * bindings with the application. As you can see, we are registering our
	 * "Registrar" implementation here. You can add your own bindings too!
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind(
			'Illuminate\Contracts\Auth\Registrar',
			'App\Services\Registrar'
        );
	}

}
