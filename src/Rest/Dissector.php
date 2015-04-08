<?php namespace Peakfijn\GetSomeRest\Rest;

use Illuminate\Http\Request;
use Peakfijn\GetSomeRest\Contracts\Anatomy as AnatomyContract;
use Peakfijn\GetSomeRest\Contracts\Dissector as DissectorContract;
use Peakfijn\GetSomeRest\Contracts\ResourceFactory as ResourceFactoryContract;

class Dissector implements DissectorContract
{
    /**
     * The resource factory to validate resources with.
     *
     * @var \Peakfijn\GetSomeRest\Contracts\ResourceFactory
     */
    protected $resources;

    /**
     * The base anatomy class to use when dissecting requests.
     *
     * @var \Peakfijn\GetSomeRest\Contracts\Anatomy
     */
    protected $anatomy;

    /**
     * Create a new dissector instance.
     *
     * @param \Peakfijn\GetSomeRest\Contracts\ResourceFactory $resources
     * @param \Peakfijn\GetSomeRest\Contracts\Anatomy $anatomy
     */
    public function __construct(
        ResourceFactoryContract $resources,
        AnatomyContract $anatomy
    ) {
        $this->resources = $resources;
        $this->anatomy = $anatomy;
    }

    /**
     * Dissect the REST information from the request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Peakfijn\GetSomeRest\Contracts\Anatomy
     */
    public function anatomy(Request $request)
    {
        $anatomy = $this->anatomy;
        $segments = $request->segments();
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
