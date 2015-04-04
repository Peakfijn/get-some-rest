<?php namespace Peakfijn\GetSomeRest\Factories;

use ReflectionException;
use RuntimeException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Str;
use Peakfijn\GetSomeRest\Exceptions\ResourceUnknownException;
use Peakfijn\GetSomeRest\Contracts\Factory as FactoryContract;

class ResourceFactory implements FactoryContract
{
    /**
     * The resource class paths, based on class name.
     *
     * @var array
     */
    protected $resources = [];

    /**
     * The binding container to fetch resource instances from.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * The string helper to determine plural/singular words.
     *
     * @var \Illuminate\Support\Str
     */
    protected $str;

    /**
     * The namespace to search for resources.
     *
     * @var string
     */
    protected $namespace;

    /**
     * Create a new instance with the string helper.
     *
     * @param \Illuminate\Contracts\Container\Container $container
     * @param \Illuminate\Support\Str                   $str
     * @param string                                    $namespace
     */
    public function __construct(Container $container, Str $str, $namespace)
    {
        $this->container = $container;
        $this->str = $str;
        $this->namespace = $namespace;
    }

    /**
     * Get a new resource from the factory, by name.
     * If nothing was found, it tries to resolve the name.
     * When also resolving fails, it throws an exception.
     *
     * @param  string $name
     * @return object|null
     */
    public function make($name)
    {
        if (!$this->contains($name) && !$this->resolve($name)) {
            throw new ResourceUnknownException($name);
        }

        $class = $this->getClassName($name);

        return $this->container->make($this->resources[$class]);
    }

    /**
     * Check if the factory has a registered resource for the provided name.
     *
     * @param  string $name
     * @return boolean
     */
    public function contains($name)
    {
        return array_key_exists($this->getClassName($name), $this->resources);
    }

    /**
     * Register an resource to the factory.
     * It only stores the class name.
     *
     * @param  string $name
     * @param  mixed  $value
     * @return object
     */
    public function register($name, $value)
    {
        $value = is_object($value)? get_class($value): $value;

        return $this->resources[$this->getClassName($name)] = trim($value, '\\');
    }

    /**
     * The resource factory is a self-signing factory.
     * It tries to lookup something, if noting can be found no default should be used.
     *
     * @throws RuntimeException
     * @param  string $name
     * @return void
     */
    public function defaults($name)
    {
        throw new RuntimeException(
            'The resource factory is self-assigning, and not using defaults'
        );
    }

    /**
     * Try to resolve a resource class from the provided name.
     *
     * @param  string $name
     * @return boolean
     */
    protected function resolve($name)
    {
        $name = $this->getClassName($name);

        try {
            $instance = $this->container->make($this->namespace .'\\'. $name);
            $this->register($name, $instance);
        } catch (ReflectionException $e) {
            return false;
        }

        return true;
    }

    /**
     * Get a cleaned class name from the provided string.
     *
     * @param  string $name
     * @return string
     */
    protected function getClassName($name)
    {
        $name = strtolower($name);
        $name = $this->str->camel($name);
        $name = $this->str->singular($name);

        return ucfirst($name);
    }
}