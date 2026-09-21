<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
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
        Schema::defaultStringLength(191);
        Paginator::useBootstrapFive();

        // Dynamically apply System Timezone from Settings
        try {
            if (Schema::hasTable('xin_system_setting')) {
                $systemTimezone = \App\Models\SystemSetting::value('system_timezone') ?? config('app.timezone', 'Asia/Kolkata');
                if (!empty($systemTimezone) && in_array($systemTimezone, \DateTimeZone::listIdentifiers())) {
                    date_default_timezone_set($systemTimezone);
                    config(['app.timezone' => $systemTimezone]);
                }
            }
        } catch (\Throwable $e) {
            // Fallback gracefully during early CLI setup or migrations
        }

        \Illuminate\Support\Facades\Blade::directive('humanDate', function ($expression) {
            return "<?php echo \App\Helpers\DateHelper::format($expression); ?>";
        });

        \Illuminate\Support\Facades\Blade::directive('humanDateTime', function ($expression) {
            return "<?php echo \App\Helpers\DateHelper::formatDateTime($expression); ?>";
        });

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

        // Share global system settings across all layouts
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('xin_system_setting')) {
                view()->share('systemSetting', \App\Models\SystemSetting::first());
            }
        } catch (\Throwable $e) {
            // Ignore during DB migrations or CLI boot
        }
    }
}
