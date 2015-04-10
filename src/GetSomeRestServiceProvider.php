<?php namespace Peakfijn\GetSomeRest;

use Illuminate\Support\ServiceProvider;
use Peakfijn\GetSomeRest\Factories\EncoderFactory;
use Peakfijn\GetSomeRest\Factories\MutatorFactory;
use Peakfijn\GetSomeRest\Factories\MethodFactory;
use Peakfijn\GetSomeRest\Factories\ResourceFactory;
use Peakfijn\GetSomeRest\Rest\Anatomy;
use Peakfijn\GetSomeRest\Rest\Dissector;

class GetSomeRestServiceProvider extends ServiceProvider
{
    /**
     * The config files to plublish.
     *
     * @var array
     */
    protected $configFiles = [
        'general',
        'routes'
    ];

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $config = $this->app->make('config');

        $this->registerEncoderFactory(
            $config->get('get-some-rest.general.encoders', []),
            $config->get('get-some-rest.general.default_encoder')
        );

        $this->registerMutatorFactory(
            $config->get('get-some-rest.general.mutators', []),
            $config->get('get-some-rest.general.default_mutator')
        );

        $this->registerResourceFactory(
            $config->get('get-some-rest.general.namespace'),
            $config->get('get-some-rest.general.resources', [])
        );

        $this->registerMethodFactory(
            $config->get('get-some-rest.general.methods', [])
        );

        $this->registerDissectorAndAnatomy();
        $this->registerSelectorAndOperator();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'Peakfijn\GetSomeRest\Contracts\EncoderFactory',
            'Peakfijn\GetSomeRest\Contracts\MutatorFactory',
            'Peakfijn\GetSomeRest\Contracts\ResourceFactory',
            'Peakfijn\GetSomeRest\Contracts\MethodFactory',
            'Peakfijn\GetSomeRest\Contracts\Anatomy',
            'Peakfijn\GetSomeRest\Contracts\Dissector',
            'Peakfijn\GetSomeRest\Contracts\Selector',
            'Peakfijn\GetSomeRest\Contracts\Operator',
        ];
    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerConfigFiles();

        $config = $this->app->make('config');

        if ($config->get('get-some-rest.routes.generate', false)) {
            $this->bindResourceRoutes(
                $this->app->make('router'),
                $config->get('get-some-rest.routes.controller'),
                $config->get('get-some-rest.routes.settings', []),
                $config->get('get-some-rest.routes.relations', false)
            );
        }
    }

    /**
     * Publish & merge the provided config files.
     *
     * @return void
     */
    protected function registerConfigFiles()
    {
        $folder = __DIR__ . '/config/';
        $publish = [];

        foreach ($this->configFiles as $file) {
            $publish[$folder . $file . '.php'] = config_path('get-some-rest/' . $file . '.php');
            $this->mergeConfigFrom($folder . $file . '.php', 'get-some-rest.' . $file);
        }

        $this->publishes($publish);
    }

    /**
     * Register the encoder factory to the container, as singleton.
     *
     * @param  array $encoders
     * @param  string $default
     * @return void
     */
    protected function registerEncoderFactory(array $encoders, $default = null)
    {
        $this->app->singleton(
            'Peakfijn\GetSomeRest\Factories\EncoderFactory',
            function ($app) use ($encoders, $default) {
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

        $this->app->bindIf(
            'Peakfijn\GetSomeRest\Contracts\EncoderFactory',
            'Peakfijn\GetSomeRest\Factories\EncoderFactory'
        );
    }

    /**
     * Register the mutator factory to the container, as singleton.
     *
     * @param  array $mutators
     * @param  string $default
     * @return void
     */
    protected function registerMutatorFactory(array $mutators, $default = null)
    {
        $this->app->singleton(
            'Peakfijn\GetSomeRest\Factories\MutatorFactory',
            function ($app) use ($mutators, $default) {
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

        $this->app->bindIf(
            'Peakfijn\GetSomeRest\Contracts\MutatorFactory',
            'Peakfijn\GetSomeRest\Factories\MutatorFactory'
        );
    }

    /**
     * Register the resource factory to the container, as singleton.
     *
     * @param  string $namespace
     * @param  array $resources
     * @return void
     */
    protected function registerResourceFactory($namespace, array $resources = array())
    {
        $this->app->singleton(
            'Peakfijn\GetSomeRest\Factories\ResourceFactory',
            function ($app) use ($namespace, $resources) {
                $factory = new ResourceFactory(
                    $app,
                    $app->make('\Illuminate\Support\Str'),
                    $namespace
                );

                foreach ($resources as $name => $resource) {
                    $factory->register($name, $resource);
                }

                return $factory;
            }
        );

        $this->app->bindIf(
            'Peakfijn\GetSomeRest\Contracts\ResourceFactory',
            'Peakfijn\GetSomeRest\Factories\ResourceFactory'
        );
    }

    /**
     * Register the method factory to the container, as singleton.
     *
     * @param  array $methods
     * @return void
     */
    protected function registerMethodFactory(array $methods)
    {
        $this->app->singleton(
            'Peakfijn\GetSomeRest\Factories\MethodFactory',
            function ($app) use ($methods) {
                $factory = new MethodFactory();
                $factory->setPrefix('$');

                foreach ($methods as $method => $class) {
                    $factory->register($method, $app->make($class));
                }

                return $factory;
            }
        );

        $this->app->bindif(
            'Peakfijn\GetSomeRest\Contracts\MethodFactory',
            'Peakfijn\GetSomeRest\Factories\MethodFactory'
        );
    }

    /**
     * Register the rest dissector and anatomy.
     *
     * @return void
     */
    protected function registerDissectorAndAnatomy()
    {
        $this->app->singleton(
            'Peakfijn\GetSomeRest\Rest\Dissector',
            function ($app) {
                $request = $app->make('request');
                $resources = $app->make('Peakfijn\GetSomeRest\Contracts\ResourceFactory');
                $methods = $app->make('Peakfijn\GetSomeRest\Contracts\MethodFactory');
                $anatomy = new Anatomy();

                return new Dissector($request, $resources, $methods, $anatomy);
            }
        );

        $this->app->bind(
            'Peakfijn\GetSomeRest\Rest\Anatomy',
            function ($app) {
                $dissector = $app->make('Peakfijn\GetSomeRest\Contracts\Dissector');

                return $dissector->anatomy();
            }
        );

        $this->app->bindIf(
            'Peakfijn\GetSomeRest\Contracts\Anatomy',
            'Peakfijn\GetSomeRest\Rest\Anatomy'
        );

        $this->app->bindIf(
            'Peakfijn\GetSomeRest\Contracts\Dissector',
            'Peakfijn\GetSomeRest\Rest\Dissector'
        );
    }

    /**
     * Register the rest selector and operator.
     *
     * @return void
     */
    public function registerSelectorAndOperator()
    {
        $this->app->bindIf(
            'Peakfijn\GetSomeRest\Contracts\Selector',
            'Peakfijn\GetSomeRest\Rest\Selector'
        );

        $this->app->bindIf(
            'Peakfijn\GetSomeRest\Contracts\Operator',
            'Peakfijn\GetSomeRest\Rest\Operator'
        );
    }

    /**
     * Bind the resource route, that covers all resource actions.
     *
     * @param  \Illuminate\Routing\Router $router
     * @param  string $controller
     * @param  array $settings (default: []),
     * @param  boolean $relations (default: false)
     * @return void
     */
    protected function bindResourceRoutes(
        $router,
        $controller,
        array $settings = array(),
        $relations = false
    ) {
        $router->group($settings, function ($router) use ($controller, $relations) {
            $router->get('/{resource}', $controller . '@index');
            $router->post('/{resource}', $controller . '@store');
            $router->get('/{resource}/{id}', $controller . '@show');
            $router->put('/{resource}/{id}', $controller . '@update');
            $router->delete('/{resource}/{id}', $controller . '@destroy');

            if ($relations) {
                $router->get('/{resource}/{id}/{relation}', $controller . '@relationIndex');
                $router->get('/{resource}/{id}/{relation}/{relatedId}', $controller . '@relationShow');
            }
        });
    }
}
