<?php namespace Peakfijn\GetSomeRest\Http;

use Illuminate\Http\Request;

class RestUrl
{
    /**
     * The namespace to search for resource classes.
     *
     * @var string
     */
    private $namespace;

    /**
     * All resource class exceptions.
     *
     * @var array
     */
    private $aliases;

    /**
     * Use the plural equivalent of the resource names.
     *
     * @var boolean
     */
    private $usePlural;

    /**
     * The main resource, with namespace.
     *
     * @var string
     */
    protected $resourceClass;

    /**
     * The main resource's id.
     *
     * @var int|string
     */
    protected $resourceId;

    /**
     * All scopes, stored with $relation => $id
     *
     * @var array
     */
    protected $scopes;

    /**
     * Create a new rest url parser.
     *
     * @param array    $segments
     * @param boolean  $plural
     * @param string   $namespace
     * @param array    $aliases
     */
    public function __construct($segments, $plural, $namespace, $aliases)
    {
        $this->usePlural = !!$plural;
        $this->namespace = $namespace;
        $this->aliases = (array)$aliases;

        $this->parse((array)$segments);
    }

    /**
     * Parse the provided url segments.
     *
     * @param  array  $segments
     * @return void
     */
    protected function parse(array $segments)
    {
        $usable = $this->findRestSegments($segments);

        if (!empty($usable)) {
            $this->parseRestSegments($usable);
        }
    }

    /**
     * Try and search for the rest segments only.
     * If something was found, an array is returned.
     * Else null will be returned.
     *
     * @param  array  $segments
     * @return array|null
     */
    private function findRestSegments(array $segments)
    {
        $usePlural = $this->usePlural;

        foreach ($segments as $segment) {
            $resource = $this->getResource($segment);
            $isPlural = $this->isPluralString($segment);

            if ($usePlural && !$isPlural || !$usePlural && $isPlural) {
                break;
            }

            if (!empty($resource)) {
                return array_slice($segments, array_search($segment, $segments));
            }
        }
    }

    /**
     * Extract information about the following rest segments.
     * These should be filtered on the start of the rest structure.
     *
     * @param  array  $segments
     * @return void
     */
    private function parseRestSegments(array $segments)
    {
        $hasClass  = count($segments) > 0;
        $hasId     = count($segments) > 1;
        $hasScopes = count($segments) > 2;

        if ($hasClass) {
            $this->resourceClass = $this->getResource($segments[0]);
        }

        if ($hasId) {
            $this->resourceId = $segments[1];
        }

        $scopes = array_slice($segments, 2);

        if (!empty($scopes)) {
            $this->parseRestScopes($scopes);
        }
    }

    /**
     * Parse all scopes in the segments.
     *
     * @param  array  $segments
     * @return void
     */
    private function parseRestScopes(array $segments)
    {
        $resource = $this->resourceClass();
        $lastMethod = null;

        foreach ($segments as $key => $segment) {
            $isMethod = $key % 2 == 0;
            $method = camel_case($segment);

            if ($isMethod) {
                $lastMethod = $method;
                $this->scopes[$method] = null;
            } else {
                $this->scopes[$lastMethod] = $segment;
            }
        }
    }

    /**
     * Get the full classname, including namespace, of the provided string.
     * It searches in the aliases, else in the namespace.
     * If nothing was found, null is returned.
     *
     * @param  string  $class
     * @return string|null
     */
    protected function getResource($class)
    {
        if ($this->isResourceAlias($class)) {
            return $this->getResourceAlias($class);
        }

        if ($this->isResourceClass($class)) {
            return $this->getResourceClass($class);
        }
    }

    /**
     * Check if the provided classname is a valid class, within the namespace.
     *
     * @param  string  $class
     * @return boolean
     */
    protected function isResourceClass($class)
    {
        return class_exists($this->namespace .'\\'. $this->getClassFromString($class));
    }

    /**
     * Get the full classname, including namespace, of the provided string.
     *
     * @param  string $class
     * @return string
     */
    protected function getResourceClass($class)
    {
        return $this->namespace .'\\'. $this->getClassFromString($class);
    }

    /**
     * Check if the provided classname is listed as alias.
     *
     * @param  string  $class
     * @return boolean
     */
    protected function isResourceAlias($class)
    {
        return array_key_exists($class, $this->aliases);
    }

    /**
     * Get the alias class.
     *
     * @param  string $class
     * @return string
     */
    protected function getResourceAlias($class)
    {
        return $this->aliases[$class];
    }

    /**
     * Get a string, equal to the provided string, with some guidelines applied.
     * These guidelines MUST be used when creating classnames.
     *
     * @see    http://www.php-fig.org/psr/psr-1/
     * @see    http://www.php-fig.org/psr/psr-2/
     * @see    http://www.php-fig.org/psr/psr-4/
     * @param  string $string
     * @return string
     */
    protected function getClassFromString($string)
    {
        $string = strtolower($string);
        $string = studly_case($string);
        $string = str_singular($string);

        return $string;
    }

    /**
     * Check if the provided string is plural.
     *
     * @param  string  $string
     * @return boolean
     */
    protected function isPluralString($string)
    {
        return $string == str_plural($string);
    }

    /**
     * Get the main resource class, without namespace.
     *
     * @return string
     */
    public function resourceName()
    {
        return class_basename($this->resourceClass);
    }

    /**
     * Get the main resource class, with namespace.
     *
     * @return string
     */
    public function resourceClass()
    {
        return $this->resourceClass;
    }

    /**
     * Get the main resource identifier.
     *
     * @return int|string
     */
    public function resourceId()
    {
        return $this->resourceId;
    }

    /**
     * Get all scopes. This returns an array where the key will be the
     * method/scope, and a possible value the identifier of this subscope.
     *
     * @return array
     */
    public function scopes()
    {
        return $this->scopes;
    }
}
