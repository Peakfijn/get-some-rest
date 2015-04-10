<?php namespace Peakfijn\GetSomeRest\Http\Controllers;

use Peakfijn\GetSomeRest\Contracts\Factories\ResourceFactory as ResourceFactoryContract;
use Peakfijn\GetSomeRest\Contracts\Rest\Anatomy as AnatomyContract;
use Peakfijn\GetSomeRest\Contracts\Rest\Operator as OperatorContract;
use Peakfijn\GetSomeRest\Exceptions\ResourceRelationUnknownException;

trait ResourceRelationShowTrait
{
    /**
     * Display a listing of the related resources, from the main resource.
     *
     * @param  \Peakfijn\GetSomeRest\Contracts\Rest\Anatomy $rest
     * @param  \Peakfijn\GetSomeRest\Contracts\Factories\ResourceFactory $resources
     * @param  \Peakfijn\GetSomeRest\Contracts\Rest\Operator $operator
     * @return mixed
     */
    public function relationShow(
        AnatomyContract $rest,
        ResourceFactoryContract $resources,
        OperatorContract $operator
    ) {
        return $this->relationShowResource($rest, $resources, $operator);
    }

    /**
     * Get a list of the related resource, from the main resource.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \Peakfijn\GetSomeRest\Exceptions\ResourceRelationUnknownException
     * @param  \Peakfijn\GetSomeRest\Contracts\Rest\Anatomy $rest
     * @param  \Peakfijn\GetSomeRest\Contracts\Factories\ResourceFactory $resources
     * @param  \Peakfijn\GetSomeRest\Contracts\Rest\Operator $operator
     * @return mixed
     */
    protected function relationShowResource(
        AnatomyContract $rest,
        ResourceFactoryContract $resources,
        OperatorContract $operator
    ) {
        $resource = $resources->make($rest)
            ->findOrFail($rest->getResourceId());

        $relation = $rest->getRelationName();

        if (!method_exists($resource, $relation)) {
            throw new ResourceRelationUnknownException();
        }

        $resource = $resource->$relation();
        $resource = $operator->execute($resource);

        return $resource->findOrFail($rest->getRelationId());
    }
}
