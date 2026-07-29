<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
            if ($user && method_exists($user, 'roleRelation')) {
                $role = $user->roleRelation;
                if ($role) {
                    if ($role->role_access === 'all') {
                        return true;
                    }
                    if (in_array($ability, $role->resource_list)) {
                        return true;
                    }
                }
            }
            return null;
        });

        view()->composer('layouts.sidebar', function ($view) {
            $menus = \App\Models\NavigationMenu::whereNull('parent_id')
                ->where('is_active', true)
                ->with(['children' => function ($q) {
                    $q->where('is_active', true)->orderBy('sort_order');
                }])
                ->orderBy('sort_order')
                ->get();
            $view->with('dynamicMenus', $menus);
        });
    }
}
