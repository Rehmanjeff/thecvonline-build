<?php

namespace App\Providers;

use App\Repositories\EmailRepository;
use App\Repositories\EmailRepositoryInterface;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\UserRepositoryInterface;

class InterfaceBindingServiceProvider extends ServiceProvider
{
    const BINDINGS = [
        UserRepositoryInterface::class => UserRepository::class,
        EmailRepositoryInterface::class => EmailRepository::class,
    ];
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        foreach(self::BINDINGS as $key => $binding) {
            $this->app->bind($key, $binding);
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
