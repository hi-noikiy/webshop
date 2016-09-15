<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class CheckActive
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Ignore this middleware if the user is not logged in
        if ($this->auth->guest()) {
            return $next($request);
        }

        // If the account is active, let them through
        if ($this->auth->user()->company->active) {
            return $next($request);
        }

        // Whoops, the account is not active, better log them out again!
        $this->auth->logout();

        return redirect('/')
            ->withErrors("Dit account is niet actief, als u denkt dat dit een fout is, neem dan contact met ons op");
    }
}
