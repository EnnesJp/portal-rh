<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Company;
use App\Models\Punch;
use App\Models\User;
use App\Policies\CompanyPolicy;
use App\Policies\PunchPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Punch::class => PunchPolicy::class,
        Company::class > CompanyPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        //
    }
}
