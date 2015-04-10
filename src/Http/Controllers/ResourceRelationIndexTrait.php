<?php namespace Peakfijn\GetSomeRest\Http\Controllers;

use Peakfijn\GetSomeRest\Contracts\Factories\ResourceFactory as ResourceFactoryContract;
use Peakfijn\GetSomeRest\Contracts\Rest\Anatomy as AnatomyContract;
use Peakfijn\GetSomeRest\Contracts\Rest\Selector as SelectorContract;
use Peakfijn\GetSomeRest\Contracts\Rest\Operator as OperatorContract;
use Peakfijn\GetSomeRest\Exceptions\ResourceRelationUnknownException;

trait ResourceRelationIndexTrait
{
    /**
     * Display a listing of the related resources, from the main resource.
     *
     * @param  \Peakfijn\GetSomeRest\Contracts\Anatomy $rest
     * @param  \Peakfijn\GetSomeRest\Contracts\ResourceFactory $resources
     * @param  \Peakfijn\GetSomeRest\Contracts\Selector $selector
     * @param  \Peakfijn\GetSomeRest\Contracts\Operator $operator
     * @return mixed
     */
    public function relationIndex(
        AnatomyContract $rest,
        ResourceFactoryContract $resources,
        SelectorContract $selector,
        OperatorContract $operator
    ) {
        return $this->relationIndexResource(
            $rest,
            $resources,
            $selector,
            $operator
        );
    }

    /**
     * Get a list of the related resource, from the main resource.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \Peakfijn\GetSomeRest\Exceptions\ResourceRelationUnknownException
     * @param  \Peakfijn\GetSomeRest\Contracts\Anatomy $rest
     * @param  \Peakfijn\GetSomeRest\Contracts\ResourceFactory $resources
     * @param  \Peakfijn\GetSomeRest\Contracts\Selector $selector
     * @param  \Peakfijn\GetSomeRest\Contracts\Operator $operator
     * @return mixed
     */
    protected function relationIndexResource(
        AnatomyContract $rest,
        ResourceFactoryContract $resources,
        SelectorContract $selector,
        OperatorContract $operator
    ) {
        $resource = $resources->make($rest)
            ->findOrFail($rest->getResourceId());

        $relation = $rest->getRelationName();

        if (!method_exists($resource, $relation)) {
            throw new ResourceRelationUnknownException();
        }

        $resource = $resource->$relation();

        $resource = $selector->filter($resource);
        $resource = $operator->execute($resource);

        return $resource->get();
    }
}
