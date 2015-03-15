<?php namespace Peakfijn\GetSomeRest\Http;

use Illuminate\Http\Request as IlluminateRequest;
use Peakfijn\GetSomeRest\Http\Exceptions\ResourceUnknownException;

class Request extends IlluminateRequest
{
    /**
     * The resource class, if requested.
     *
     * @var string
     */
    protected $resourceName;

    /**
     * The resource id, if requested.
     *
     * @var string
     */
    protected $resourceId;

    /**
     * Sets the parameters for this request.
     *
     * This method also re-initializes all properties.
     *
     * @param array  $query      The GET parameters
     * @param array  $request    The POST parameters
     * @param array  $attributes The request attributes (parameters parsed from the PATH_INFO, ...)
     * @param array  $cookies    The COOKIE parameters
     * @param array  $files      The FILES parameters
     * @param array  $server     The SERVER parameters
     * @param string $content    The raw body data
     *
     * @api
     */
    public function initialize(
        array $query = array(),
        array $request = array(),
        array $attributes = array(),
        array $cookies = array(),
        array $files = array(),
        array $server = array(),
        $content = null
    ) {
        parent::initialize(
            $query,
            $request,
            $attributes,
            $cookies,
            $files,
            $server,
            $content
        );

        $this->initializeRestInformation();
    }

    /**
     * Get an instance of the requested resource.
     * If an id was supplied within the URL, that resource will be returned.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \Peakfijn\GetSomeRest\Http\Exceptions\ResourceUnknownException
     * @return object
     */
    public function resource()
    {
        if ($this->resourceName) {
            $resource = app($this->resourceName);

            if ($this->resourceId) {
                $resource = $resource->findOrFail($this->resourceId);
            }

            return $resource;
        }

        throw new ResourceUnknownException();
    }

    /**
     * Try to extract the resource information from a rest full url.
     *
     * @return void
     */
    private function initializeRestInformation()
    {
        $segments = $this->segments();
        $information = array_slice($segments, -2, 2);

        $first = array_key_exists(0, $information)? $information[0]: null;
        $last = array_key_exists(1, $information)? $information[1]: null;

        if ($first && $resource = $this->getValidResourceClass($first)) {
            $this->resourceName = $resource;
            $this->resourceId = !empty($last)? $last: null;
        } elseif ($last && $resource = $this->getValidResourceClass($last)) {
            $this->resourceName = $resource;
        }
    }

    /**
     * Get the cleaned resource name.
     *
     * @param  string $name
     * @return string
     */
    private function getCleanResourceName($name)
    {
        $name = camel_case($name);
        $name = ucfirst($name);
        $name = str_singular($name);

        return $name;
    }

    /**
     * Get a valid resource class from the provided name.
     * If the response is null, the name is invalid.
     *
     * @param  string $name
     * @return string|null
     */
    private function getValidResourceClass($name)
    {
        $name = $this->getCleanResourceName($name);
        $namespace = config('get-some-rest.resources.namespace');

        if (class_exists($namespace .'\\'. $name)) {
            return $namespace .'\\'. $name;
        }
    }
}
