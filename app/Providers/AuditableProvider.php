<?php

namespace App\Providers;

use App\Audit\IsAuditable;
use App\Observers\AuditableObserver;
use Illuminate\Support\ServiceProvider;

class AuditableProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('auditableModels', function() {
            return collect(config('auditable.models', []))
                ->filter(function($model) {
                    return is_subclass_of($model, IsAuditable::class);
                })->map(function($model) {
                    return (new \ReflectionClass($model))->newInstanceWithoutConstructor();
                });
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('auditableModels')->each(function(IsAuditable $model) {
            $model::observe(AuditableObserver::class);
        });
    }
}
