<?php

namespace enesyurtlu\theman;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class TheManServiceProvider extends ServiceProvider
{
    const CONFIG_PATH = __DIR__ . '/../config/theman.php';

    public function boot()
    {
        $this->publishes([
            self::CONFIG_PATH => config_path('theman.php'),
        ], 'config');

        $this->app['TheMan']->setTheme($this->app['request']);

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
            'middleware' => 'web',
            'namespace' => 'modules\core\controllers',
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
        $routeFile = base_path('themes/' . $this->app['TheMan']->getTheme() . '/routes/' . $routeFile . '.php');
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
            'namespace' => 'modules\core\controllers',
            'prefix' => 'api',
        ], function ($router) {
            $this->loadRouteFile('api');
        });

        Route::group([
            'middleware' => ['api'],
            'namespace' => 'modules\core\controllers',
            'prefix' => 'api',
        ], function ($router) {
            $this->loadRouteFile('api-no-auth');
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            'theman'
        );

        $this->app->bind('TheMan', function () {
            return new enesyurtlu\TheMan();
        });

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
            return new enesyurtlu\theman\ThemeFileViewFinder($app['files'], $paths);
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
        $this->app->singleton('TheMan', function () {
            return new TheMan();
        });
    }
}
