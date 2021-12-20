<?php

namespace Najibismail\LaravelLokalise;

use Illuminate\Support\ServiceProvider;

class LaravelLokaliseServiceProvider extends ServiceProvider
{
    const ROOT = __DIR__ . '/../';
    
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(self::ROOT . 'config/lokalise.php', 'lokalise');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            self::ROOT . 'config/lokalise.php' => config_path('lokalise'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\UploadCommand::class,
                Commands\DownloadCommand::class,
                Commands\PublishCommand::class,
            ]);
        }
    }
}
