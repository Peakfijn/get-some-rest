<?php namespace Peakfijn\GetSomeRest;

use Illuminate\Support\ServiceProvider;
use Peakfijn\GetSomeRest\Factories\EncoderFactory;
use Peakfijn\GetSomeRest\Factories\MutatorFactory;

class GetSomeRestServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $config = $this->app->make('config');

        $this->registerEncoderFactory(
            $config->get('get-some-rest.encoders', []),
            $config->get('get-some-rest.default-encoder')
        );

        $this->registerMutatorFactory(
            $config->get('get-some-rest.mutators', []),
            $config->get('get-some-rest.default-mutator')
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'Peakfijn\GetSomeRest\Factories\EncoderFactory',
            'Peakfijn\GetSomeRest\Factories\MutatorFactory'
        ];
    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $config = $this->app->make('config');
        $configFile = __DIR__ .'/config/config.php';

        $this->mergeConfigFrom($configFile, 'get-some-rest');
        $this->publishes([
            $configFile => config_path('get-some-rest.php')
        ]);

        // if ($config->get('get-some-rest.generate-routes', false)) {
        //     $this->bindResourceRoutes(
        //         $this->app->make('router'),
        //         $config->get('get-some-rest.route-controller'),
        //         $config->get('get-some-rest.route-settings')
        //     );
        // }
    }

    /**
     * Register the encoder factory to the container, as singleton.
     *
     * @param  array  $encoders
     * @param  string $default
     * @return void
     */
    protected function registerEncoderFactory(array $encoders, $default = null)
    {
        $this->app->singleton(
            '\Peakfijn\GetSomeRest\Factories\EncoderFactory',
            function ($app) use ($encoders, $default)
            {
                $factory = new EncoderFactory();

                foreach ($encoders as $name => $encoder) {
                    $factory->register($name, $app->make($encoder));
                }

                if (!empty($default)) {
                    $factory->defaults($default);
                }

                return $factory;
            }
        );
    }

    /**
     * Register the mutator factory to the container, as singleton.
     *
     * @param  array  $mutators
     * @param  string $default
     * @return void
     */
    protected function registerMutatorFactory(array $mutators, $default = null) {
        $this->app->singleton(
            '\Peakfijn\GetSomeRest\Factories\MutatorFactory',
            function ($app) use ($mutators, $default)
            {
                $factory = new MutatorFactory();

                foreach ($mutators as $name => $mutator) {
                    $factory->register($name, $app->make($mutator));
                }

                if (!empty($default)) {
                    $factory->defaults($default);
                }

                return $factory;
            }
        );
    }

    /**
     * Bind the resource route, that covers all resource actions.
     *
     * @param  \Illuminate\Routing\Router $router
     * @param  string                     $controller
     * @param  array                      $settings (default: [])
     * @return void
     */
    protected function bindResourceRoutes($router, $controller, array $settings = array())
    {
        $router->group($settings, function ($router) use ($controller)
        {
            $router->get('/{resource}',         $controller .'@index');
            $router->post('/{resource}',        $controller .'@store');
            $router->get('/{resource}/{id}',    $controller .'@show');
            $router->put('/{resource}/{id}',    $controller .'@update');
            $router->delete('/{resource}/{id}', $controller .'@destroy');
        });
    }
}
