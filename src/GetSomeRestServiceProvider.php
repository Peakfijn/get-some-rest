<?php namespace Peakfijn\GetSomeRest;

use Illuminate\Routing\Console\MiddlewareMakeCommand;
use Illuminate\Support\ServiceProvider;
use Peakfijn\GetSomeRest\Auth\Guard;
use Peakfijn\GetSomeRest\Auth\Shield;
use Peakfijn\GetSomeRest\Auth\Storage\AccessTokenStorage;
use Peakfijn\GetSomeRest\Auth\Storage\AuthCodeStorage;
use Peakfijn\GetSomeRest\Auth\Storage\ClientStorage;
use Peakfijn\GetSomeRest\Auth\Storage\RefreshTokenStorage;
use Peakfijn\GetSomeRest\Auth\Storage\ScopeStorage;
use Peakfijn\GetSomeRest\Auth\Storage\SessionStorage;
use Peakfijn\GetSomeRest\Http\Request;
use Peakfijn\GetSomeRest\Http\Url\Url;
use Peakfijn\GetSomeRest\Http\Url\Resolvers\ResourceResolver;

class GetSomeRestServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Peakfijn\GetSomeRest\Http\Request', function ($app)
        {
            $origin = $app->make('request');

            $request = new Request(
                $origin->query->all(),
                $origin->request->all(),
                $origin->attributes->all(),
                $origin->cookies->all(),
                $origin->files->all(),
                $origin->server->all(),
                $origin->content
            );

            return $request;
        });

        $this->app->bind('Peakfijn\GetSomeRest\Http\Url\Resolvers\ResourceResolver', function ($app)
        {
            $config = $app['config'];

            return new ResourceResolver(
                $app['Illuminate\Container\Container'],
                $config['get-some-rest.namespace'],
                $config['get-some-rest.aliases']
            );
        });

        $this->app->bind('Peakfijn\GetSomeRest\Http\Url\Url', function ($app)
        {
            return new Url(
                $app['Illuminate\Http\Request'],
                $app['Peakfijn\GetSomeRest\Http\Url\Resolvers\ResourceResolver']
            );
        });

        $this->app->singleton('Peakfijn\GetSomeRest\Auth\Shield', function ()
        {
            return new Shield(
                new SessionStorage(),
                new AccessTokenStorage(),
                new RefreshTokenStorage(),
                new ClientStorage(),
                new ScopeStorage(),
                new AuthCodeStorage()
            );
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'Peakfijn\GetSomeRest\Http\Request',
            'Peakfijn\GetSomeRest\Http\Url\Url',
            'Peakfijn\GetSomeRest\Http\Url\Resolvers\ResourceResolver'
        ];
    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $config = $this->app['config'];
        $configFile = __DIR__ .'/config/config.php';

        // Publish config
        $this->mergeConfigFrom($configFile, 'get-some-rest');
        $this->publishes([
            $configFile => config_path('get-some-rest.php')
        ]);

        // Publish migrations
        $this->publishes([
            __DIR__.'/database/migrations/' => base_path('/database/migrations')
        ], 'migrations');

        if ($config->get('get-some-rest.generate-routes', false)) {
            $this->bindResourceRoutes(
                $this->app['router'],
                $config->get('get-some-rest.route-controller'),
                $config->get('get-some-rest.route-settings')
            );
        }
    }

    /**
     * Register the resource route, that covers all resource actions.
     *
     * @param  \Illuminate\Routing\Router $router
     * @param  string                     $controller
     * @param  array                      $settings (default: [])
     * @return void
     */
    protected function bindResourceRoutes($router, $controller, array $settings = [])
    {
        $router->group($settings, function ($router) use ($controller)
        {
            $router->get('/{resource}',                $controller .'@index');
            $router->post('/{resource}',               $controller .'@store');
            $router->get('/{resource}/{id}/{scopes?}', $controller .'@show')->where('scopes', '(.*)');
            $router->put('/{resource}/{id}',           $controller .'@update');
            $router->delete('/{resource}/{id}',        $controller .'@destroy');
        });
    }
}
