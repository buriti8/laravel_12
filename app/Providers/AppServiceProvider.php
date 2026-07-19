<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Laravel\Pulse\Facades\Pulse;

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
        $this->dateTimeBladeDirectives();

        Paginator::useBootstrap();

        Gate::define('viewPulse', function (User $user) {
            return $user->isAdmin();
        });

        Pulse::user(fn($user) => [
            'name'   => $user->name ?? '',
            'extra'  => ($user->username ?? '') . ' | ' . (mb_strtoupper($user->getRoleNames()->implode(', ') ?? '')),
        ]);

        Schema::defaultStringLength(255);

        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        Gate::before(function ($user, $ability) {
            if ($user->hasRole('Administrador')) {
                return true;
            }
        });

        Blade::if('permission', function (array $permissions) {
            return validatePermission($permissions);
        });
    }

    private function dateTimeBladeDirectives()
    {
        $dateFormat = config('app.date_format', 'Y-m-d');
        $timeFormat = config('app.time_format', 'H:i');
        $dateTimeFormat = $dateFormat . ' ' . $timeFormat;

        Blade::directive('formatDate', function ($expression) use ($dateFormat) {
            return "<?php echo $expression ? ($expression)->format('$dateFormat') : ''; ?>";
        });

        Blade::directive('formatTime', function ($expression) use ($timeFormat) {
            return "<?php echo $expression ? ($expression)->format('$timeFormat') : ''; ?>";
        });

        Blade::directive('formatDateTime', function ($expression) use ($dateTimeFormat) {
            return "<?php echo $expression ? ($expression)->format('$dateTimeFormat') : ''; ?>";
        });

        Blade::directive('upper', function ($expression) {
            return "<?php echo \Illuminate\Support\Str::upper($expression); ?>";
        });

        Blade::directive('formatNumber', function ($expression) {
            $params = explode(',', $expression);
            $decimalSeparator = config('app.number_dec_sep', '.');
            $groupSeparator = config('app.number_grp', ',');

            return "<?php
                \$value = $params[0];
                echo rtrim(rtrim(number_format(\$value, 3, '$decimalSeparator', '$groupSeparator'), '0'), '$decimalSeparator');
            ?>";
        });
    }
}
