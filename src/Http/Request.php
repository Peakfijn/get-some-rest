<?php namespace Peakfijn\GetSomeRest\Http;

use RuntimeException;
use Illuminate\Http\Request as IlluminateRequest;
use Peakfijn\GetSomeRest\Http\Exceptions\ResourceUnknownException;
use Peakfijn\GetSomeRest\Http\Url\Url;

class Request extends IlluminateRequest
{
    /**
     * The Rest URL parser.
     *
     * @var \Peakfijn\GetSomeRest\Http\Url\Url
     */
    protected $restUrl;

    /**
     * Sets the parameters for this request.
     *
     * This method also re-initializes all properties.
     *
     * @param array  $query      The GET parameters
     * @param array  $request    The POST parameters*
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

        $this->restUrl = app('Peakfijn\GetSomeRest\Http\Url\Url');
    }

    /**
     * Get an instance of the requested resource.
     * If an id was supplied within the URL, that resource will be returned.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return object
     */
    public function resource()
    {
        $resource = app($this->resourceClass());

        if ($id = $this->restUrl->resourceId()) {
            $resource = $resource->findOrFail($id);
        }

        return $resource;
    }

    /**
     * Get the name of the requested resource.
     * Note, this includes the namespace.
     *
     * @throws \Peakfijn\GetSomeRest\Http\Exceptions\ResourceUnknownException
     * @return string
     */
    public function resourceClass()
    {
        if (! $class = $this->restUrl->resourceClass()) {
            throw new ResourceUnknownException();
        }

        return $class;
    }

    /**
     * Get the name of the reqested resource.
     * Note, this does NOT include the namespace.
     *
     * @throws \Peakfijn\GetSomeRest\Http\Exceptions\ResourceUnknownException
     * @return string
     */
    public function resourceName()
    {
        if (! $name = $this->restUrl->resourceName()) {
            throw new ResourceUnknownException();
        }

        return $name;
    }

    /**
     * Get the fully event name/path that will be called when an action occured.
     * Note, please use the past tense of the rest action.
     *
     * @return string
     */
    public function resourceEventName()
    {
        $actions = config('get-some-rest.events');
        $namespace = config('get-some-rest.namespace');

        $resource = $this->resourceName();
        $action = $actions[$this->action()];

        return $namespace .'\\Events\\'. $resource .'Is'. ucfirst($action);
    }

    /**
     * Get the requested rest action.
     * It will be one of the following values:
     *   - index
     *   - show
     *   - store
     *   - update
     *   - destroy
     *
     * @return string
     */
    public function action()
    {
        $method = $this->method();
        $id = $this->restUrl->resourceId();

        switch ($method) {
            case 'GET': return empty($id)? 'index': 'show';
            case 'POST': return 'store';
            case 'PUT': return 'update';
            case 'DELETE': return 'destroy';
        }
    }
}
