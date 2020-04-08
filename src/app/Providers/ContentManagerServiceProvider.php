<?php
/**
 * @author SJ
 * @date   2019.12.23
 */

namespace Restart\ContentManager\App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Http\Request;

class ContentManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
        $root = __DIR__.'/../..';

        $this->loadRoutesFrom($root.'/routes/content-manager.php');
        $this->loadMigrationsFrom($root.'/database/migrations');
        $this->loadViewsFrom($root.'/resources/views', 'content-manager');

        $this->publishes([
            $root.'/config/content-manager.php' => config_path('content-manager.php'),
        ], 'config');

        $this->publishes([
            $root.'/assets' => public_path('assets/content-manager'),
        ], 'assets');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $root = __DIR__.'/../..';

        $this->mergeConfigFrom(
            $root.'/config/content-manager.php', 'content-manager'
        );
    }
}
