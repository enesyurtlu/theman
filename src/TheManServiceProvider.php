<?php

namespace enesyurtlu\theman;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class TheManServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/theman.php' => config_path('theman.php'),
        ]);

        $this->app['theman']->setTheme($this->app['request']);

        $this->mapWebRoutes();

        $this->mapApiRoutes();
    }

    /**
     * Define the "web" routes for the theme.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::group([
            'middleware' => 'web'
        ], function ($router) {
            $this->loadRouteFile('web');
        });
    }

    /**
     * Load route file, but only after checking whether it exists in the theme.
     *
     * @return void
     */
    protected function loadRouteFile($routeFile)
    {
        $routeFile = base_path('themes/' . app('theme') . '/routes/' . $routeFile . '.php');
        if (file_exists($routeFile)) {
            require $routeFile;
        }
    }

    /**
     * Define the "api" routes for the theme. Routes are in two groups, those requiring
     * a user to be authenticated and another for routes that don't require any authentication.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::group([
            'middleware' => ['api', 'auth:api'],
            'prefix' => 'api',
        ], function ($router) {
            $this->loadRouteFile('api');
        });

        Route::group([
            'middleware' => ['api'],
            'prefix' => 'api',
        ], function ($router) {
            $this->loadRouteFile('api-no-auth');
        });
    }

    public function register()
    {
        $this->registerViewFinder();

        $this->registerThemes();
    }

    /**
     * Re-register the view finder to use local copy.
     *
     * @return void
     */
    protected function registerViewFinder()
    {
        $this->app->singleton('view.finder', function ($app) {
            $paths = $app['config']['view.paths'];
            return new ThemeFileViewFinder($app['files'], $paths);
        });

        // Apply this finder to the already-registered view factory
        $this->app['view']->setFinder($this->app['view.finder']);
    }

    /**
     * Register the themes service.
     *
     * @return void
     */
    protected function registerThemes()
    {
        $this->app->singleton('theman', function () {
            return new TheMan();
        });
    }
}
