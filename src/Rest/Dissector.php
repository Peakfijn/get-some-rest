<?php namespace Peakfijn\GetSomeRest\Rest;

use Illuminate\Http\Request;
use Peakfijn\GetSomeRest\Contracts\Factories\ResourceFactory as ResourceFactoryContract;
use Peakfijn\GetSomeRest\Contracts\Factories\MethodFactory as MethodFactoryContract;
use Peakfijn\GetSomeRest\Contracts\Rest\Anatomy as AnatomyContract;
use Peakfijn\GetSomeRest\Contracts\Rest\Dissector as DissectorContract;

class Dissector implements DissectorContract
{
    /**
     * The default request to use.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * The resource factory to validate resources with.
     *
     * @var \Peakfijn\GetSomeRest\Contracts\ResourceFactory
     */
    protected $resources;

    /**
     * The method factory to retrieve the method prefix from.
     *
     * @var \Peakfijn\GetSomeRest\Contracts\MethodFactory
     */
    protected $methods;

    /**
     * The base anatomy class to use when dissecting requests.
     *
     * @var \Peakfijn\GetSomeRest\Contracts\Anatomy
     */
    protected $anatomy;

    /**
     * Create a new dissector instance.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Peakfijn\GetSomeRest\Contracts\ResourceFactory $resources
     * @param \Peakfijn\GetSomeRest\Contracts\MethodFactory $methods
     * @param \Peakfijn\GetSomeRest\Contracts\Anatomy $anatomy
     */
    public function __construct(
        Request $request,
        ResourceFactoryContract $resources,
        MethodFactoryContract $methods,
        AnatomyContract $anatomy
    ) {
        $this->request = $request;
        $this->resources = $resources;
        $this->methods = $methods;
        $this->anatomy = $anatomy;
    }

    /**
     * Dissect the REST information from the request.
     *
     * @return \Peakfijn\GetSomeRest\Contracts\Anatomy
     */
    public function anatomy()
    {
        $anatomy = $this->anatomy;
        $segments = $this->request->segments();
        $segments = array_splice($segments, -4);

        while ($segment = array_shift($segments)) {
            if ($this->isValidResource($segment)) {
                $anatomy = $anatomy->withResourceName($segment);
                break;
            }
        }

        if (count($segments) > 0) {
            $anatomy = $anatomy->withResourceId($segments[0]);
        }

        if (count($segments) > 1) {
            $name = $this->getMethodName($segments[1]);

            $anatomy = $anatomy->withRelationName($name);
        }

        if (count($segments) > 2) {
            $anatomy = $anatomy->withRelationId($segments[2]);
        }

        return $anatomy;
    }

    /**
     * Dissect the requested REST methods.
     *
     * @return array
     */
    public function methods($removePrefix = true)
    {
        $prefix = $this->methods->getPrefix();
        $methods = [];

        foreach ($this->request->input() as $key => $value) {
            if (strpos($key, $prefix) === 0) {
                if ($removePrefix) {
                    $key = substr($key, strlen($prefix));
                }

                $methods[$key] = $value;
            }
        }

        return $methods;
    }

    /**
     * Dissect the requested REST filters.
     *
     * @return mixed
     */
    public function filters()
    {
        $methods = $this->methods(false);
        $input = $this->request->input();
        $filters = array_diff_key($input, $methods);

        foreach ($filters as &$filter) {
            $filter = explode(',', $filter);
        }

        return $filters;
    }

    /**
     * Try and validate the resource name to the resource factory.
     *
     * @param  string $name
     * @return boolean
     */
    protected function isValidResource($name)
    {
        $contained = $this->resources->contains($name);
        $resolved = $this->resources->resolve($name);

        return $contained || $resolved;
    }

    /**
     * Get a clean class name from the provided string.
     *
     * @param  string $name
     * @return string
     */
    protected function getMethodName($name)
    {
        return $this->resources->getMethodName($name);
    }
}
