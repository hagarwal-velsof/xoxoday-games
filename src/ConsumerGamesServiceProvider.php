<?php

namespace Xoxoday\Games;

use Illuminate\Support\ServiceProvider;

class ConsumerGamesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        
        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
         // Publish assets
         $this->publishes([
            __DIR__.'/config/xogames.php' => config_path('xogames.php'),
          ], 'games_files');
    }
}
