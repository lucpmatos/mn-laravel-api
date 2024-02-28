<?php

namespace App\Providers;

use App\Interfaces\AddressRepositoryInterface;
use App\Interfaces\CityRepositoryInterface;
use App\Interfaces\StateRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\AddressRepository;
use App\Repositories\CityRepository;
use App\Repositories\StateRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(StateRepositoryInterface::class, StateRepository::class);
        $this->app->bind(CityRepositoryInterface::class, CityRepository::class);
        $this->app->bind(AddressRepositoryInterface::class, AddressRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
