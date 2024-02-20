<?php

use Illuminate\Support\ServiceProvider;

class TabadulServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/tabadul.php' => config_path('tabadul.php'),
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/tabadul.php', 'tabadul'
        );
    }
}
