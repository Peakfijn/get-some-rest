<?php namespace Peakfijn\GetSomeRest\Http\Controllers;

use Peakfijn\GetSomeRest\Contracts\Anatomy as AnatomyContract;
use Peakfijn\GetSomeRest\Contracts\ResourceFactory as ResourceFactoryContract;
use Peakfijn\GetSomeRest\Exceptions\ResourceRelationUnknownException;

trait ResourceRelationIndexTrait
{
    /**
     * Display a listing of the related resources, from the main resource.
     *
     * @param  \Peakfijn\GetSomeRest\Contracts\Anatomy $rest
     * @param  \Peakfijn\GetSomeRest\Contracts\ResourceFactory $resources
     * @return mixed
     */
    public function relationIndex(
        AnatomyContract $rest,
        ResourceFactoryContract $resources
    ) {
        return $this->relationIndexResource($rest, $resources);
    }

    /**
     * Get a list of the related resource, from the main resource.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \Peakfijn\GetSomeRest\Exceptions\ResourceRelationUnknownException
     * @param  \Peakfijn\GetSomeRest\Contracts\Anatomy $rest
     * @param  \Peakfijn\GetSomeRest\Contracts\ResourceFactory $resources
     * @return mixed
     */
    protected function relationIndexResource(
        AnatomyContract $rest,
        ResourceFactoryContract $resources
    ) {
        $resource = $resources->make($rest)
            ->findOrFail($rest->getResourceId());

        $relation = $rest->getRelationName();

        if (!method_exists($resource, $relation)) {
            throw new ResourceRelationUnknownException();
        }

        return $resource->$relation()
            ->get();
    }
}
