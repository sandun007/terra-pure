<?php

namespace App\Providers;

use App\Models\WholesalerProduct;
use App\Policies\WholesalerProductPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        WholesalerProduct::class => WholesalerProductPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Additional gates can be defined here
        Gate::define('manage-wholesaler-prices', function ($employee) {
            return $employee instanceof \App\Models\Employee;
        });
    }
}
