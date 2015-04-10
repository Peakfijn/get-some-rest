<?php namespace Peakfijn\GetSomeRest\Http\Controllers;

use Peakfijn\GetSomeRest\Contracts\Factories\ResourceFactory as ResourceFactoryContract;
use Peakfijn\GetSomeRest\Contracts\Rest\Anatomy as AnatomyContract;
use Peakfijn\GetSomeRest\Contracts\Rest\Operator as OperatorContract;

trait ResourceShowTrait
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Peakfijn\GetSomeRest\Contracts\Rest\Anatomy $rest
     * @param  \Peakfijn\GetSomeRest\Contracts\Factories\ResourceFactory $resources
     * @param  \Peakfijn\GetSomeRest\Contracts\Rest\Operator $operator
     * @return mixed
     */
    public function show(
        AnatomyContract $rest,
        ResourceFactoryContract $resources,
        OperatorContract $operator
    ) {
        return $this->showResource($rest, $resources, $operator);
    }

    /**
     * Get a single resource, by it's primary key.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @param  \Peakfijn\GetSomeRest\Contracts\Rest\Anatomy $rest
     * @param  \Peakfijn\GetSomeRest\Contracts\Factories\ResourceFactory $resources
     * @param  \Peakfijn\GetSomeRest\Contracts\Rest\Operator $operator
     * @return mixed
     */
    protected function showResource(
        AnatomyContract $rest,
        ResourceFactoryContract $resources,
        OperatorContract $operator
    ) {
        $resource = $resources->make($rest);
        $resource = $operator->execute($resource);

        return $resource->findOrFail($rest->getResourceId());
    }
}
