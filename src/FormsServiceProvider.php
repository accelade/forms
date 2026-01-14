<?php

declare(strict_types=1);

namespace Accelade\Forms;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class FormsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/forms.php', 'forms');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'forms');

        $this->configurePublishing();
        $this->configureComponents();
        $this->configureRoutes();
    }

    /**
     * Configure publishing.
     */
    protected function configurePublishing(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../config/forms.php' => config_path('forms.php'),
        ], 'forms-config');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/forms'),
        ], 'forms-views');
    }

    /**
     * Configure Blade components.
     */
    protected function configureComponents(): void
    {
        // Register anonymous Blade components
        Blade::anonymousComponentPath(__DIR__.'/../resources/views/components', 'forms');
    }

    /**
     * Configure demo routes.
     */
    protected function configureRoutes(): void
    {
        if (! config('forms.demo.enabled', false)) {
            return;
        }

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }
}
