<?php

namespace WTG\Providers;

use WTG\Services\AuthService;
use WTG\Services\RegistrationService;
use WTG\Contracts\Services\AuthServiceContract;
use WTG\Contracts\Services\RegistrationServiceContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

/**
 * Class AuthServiceProvider
 *
 * @package     WTG
 * @subpackage  Providers
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'WTG\Model' => 'WTG\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $this->app->bind(AuthServiceContract::class, AuthService::class);
        $this->app->bind(RegistrationServiceContract::class, RegistrationService::class);
    }
}
