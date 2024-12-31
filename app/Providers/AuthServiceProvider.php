<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Basic permissions (Level 1,2,3)
        Gate::define('view_maintenance', function ($user) {
            return $user->hasAnyRole(['user_*_level_1', 'user_*_level_2', 'user_*_level_3']);
        });

        Gate::define('create_maintenance', function ($user) {
            return $user->hasAnyRole(['user_*_level_1', 'user_*_level_2', 'user_*_level_3']);
        });

        Gate::define('view_asset_requests', function ($user) {
            return $user->hasAnyRole(['user_*_level_1', 'user_*_level_2', 'user_*_level_3']);
        });

        Gate::define('create_asset_requests', function ($user) {
            return $user->hasAnyRole(['user_*_level_1', 'user_*_level_2', 'user_*_level_3']);
        });

        // Gate untuk level akses
        Gate::define('level_3', function ($user) {
            $hasRole = $user->roles()
                           ->where(function($query) {
                               $query->where('name', 'like', '%_level_3')
                                     ->orWhere('name', 'like', '%_level_3%');
                           })
                           ->exists();
            \Log::info('Level 3 check for user '.$user->id_user, ['has_role' => $hasRole]);
            return $hasRole;
        });

        Gate::define('level_2', function ($user) {
            $hasRole = $user->roles()
                           ->where(function($query) {
                               $query->where('name', 'like', '%_level_2')
                                     ->orWhere('name', 'like', '%_level_2%');
                           })
                           ->exists();
            \Log::info('Level 2 check for user '.$user->id_user, ['has_role' => $hasRole]);
            return $hasRole;
        });

        Gate::define('level_1', function ($user) {
            $hasRole = $user->roles()
                           ->where(function($query) {
                               $query->where('name', 'like', '%_level_1')
                                     ->orWhere('name', 'like', '%_level_1%');
                           })
                           ->exists();
            \Log::info('Level 1 check for user '.$user->id_user, ['has_role' => $hasRole]);
            return $hasRole;
        });

        // Permission lainnya
        Gate::define('view_assets', function ($user) {
            return $user->hasAnyRole(['*_level_1', '*_level_2', '*_level_3']);
        });
    }
}
