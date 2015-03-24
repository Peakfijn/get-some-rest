<?php namespace Peakfijn\GetSomeRest\Http\Url\Resolvers;

use Peakfijn\GetSomeRest\Contracts\Resolver;

class ResourceResolver implements Resolver
{
    /**
     * The namespace to search for resources.
     *
     * @var string
     */
    protected $namespace;

    /**
     * All exceptions with their full class name.
     *
     * @var array
     */
    protected $aliases;

    /**
     * Use only plural resource names.
     *
     * @var boolean
     */
    protected $usePlural;

    /**
     * Create a new resource resolver.
     *
     * @param string  $namespace
     * @param array   $aliases    (default: [])
     * @param boolean $plural     (default: true)
     */
    public function __construct(
        $namespace,
        array $aliases = array(),
        $plural = true
    ) {
        $this->namespace = $namespace;
        $this->aliases = $aliases;
        $this->usePlural = !!$plural;
    }

    /**
     * Get a clean class name, from string.
     * This uses the PHP-FIG code guidelines.
     *
     * @see    http://www.php-fig.org/psr/psr-1/
     * @see    http://www.php-fig.org/psr/psr-2/
     * @see    http://www.php-fig.org/psr/psr-4/
     * @param  string $string
     * @return string
     */
    protected function getClassNameFromString($string)
    {
        $string = strtolower($string);
        $string = camel_case($string);
        $string = str_singular($string);

        return ucfirst($string);
    }

    /**
     * Get the full class name and namespace from the resource.
     *
     * @param  string $resource
     * @return object|null
     */
    public function resolve($resource)
    {
        $isPlural = str_plural($resource) == $resource;

        if ($this->usePlural && !$isPlural || !$this->usePlural && $isPlural) {
            return;
        }

        if ($alias = $this->getAlias($resource)) {
            return $alias;
        }

        $resource = $this->getClassNameFromString($resource);

        if ($class = $this->getClass($resource)) {
            return $class;
        }
    }

    /**
     * Check if the resource is a class, within the defined namespace.
     *
     * @param  string  $resource
     * @return boolean
     */
    public function isClass($resource)
    {
        return class_exists($this->namespace .'\\'. $resource);
    }

    /**
     * Get the class name from the resource.
     *
     * @param  string $resource
     * @return string|null
     */
    public function getClass($resource)
    {
        if ($this->isClass($resource)) {
            return $this->namespace .'\\'. $resource;
        }
    }

    /**
     * Check if the resource is defined as alias.
     *
     * @param  string  $resource
     * @return boolean
     */
    public function isAlias($resource)
    {
        return array_key_exists($resource, $this->aliases);
    }

    /**
     * Get the class name from a resource alias.
     *
     * @param  string $resource
     * @return string|null
     */
    public function getAlias($resource)
    {
        if ($this->isAlias($resource)) {
            return $this->aliases[$resource];
        }
    }

    /**
     * Get the defined namespace in which it will search for resource classes.
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * Get all defined aliases.
     *
     * @return array
     */
    public function getAliases()
    {
        return $this->aliases;
    }

    /**
     * Check if the resolver is only using plural or singular names.
     *
     * @return boolean
     */
    public function usingPlural()
    {
        return $this->usePlural;
    }
}
