<?php namespace Peakfijn\GetSomeRest;

use Illuminate\Routing\Console\MiddlewareMakeCommand;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Peakfijn\GetSomeRest\Http\Request;

class GetSomeRestServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Peakfijn\GetSomeRest\Http\Request', function($app)
        {
            $request = $app->make('request');

            $rest = new Request(
                $request->query->all(),
                $request->request->all(),
                $request->attributes->all(),
                $request->cookies->all(),
                $request->files->all(),
                $request->server->all(),
                $request->content
            );

            return $rest;
        });
    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/config.php' => config_path('get-some-rest.php')
        ]);

        $this->mergeConfigFrom(
            __DIR__ . '/config/config.php', 'get-some-rest'
        );
    }
}
