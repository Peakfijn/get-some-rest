<?php namespace Peakfijn\GetSomeRest\Http\Controllers;

use Peakfijn\GetSomeRest\Contracts\Anatomy as AnatomyContract;
use Peakfijn\GetSomeRest\Contracts\ResourceFactory as ResourceFactoryContract;
use Peakfijn\GetSomeRest\Contracts\Selector as SelectorContract;
use Peakfijn\GetSomeRest\Contracts\Operator as OperatorContract;

trait ResourceIndexTrait
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Peakfijn\GetSomeRest\Contracts\Anatomy $rest
     * @param  \Peakfijn\GetSomeRest\Contracts\ResourceFactory $resources
     * @param  \Peakfijn\GetSomeRest\Contracts\Selector $selector
     * @param  \Peakfijn\GetSomeRest\Contracts\Operator $operator
     * @return mixed
     */
    public function index(
        AnatomyContract $rest,
        ResourceFactoryContract $resources,
        SelectorContract $selector,
        OperatorContract $operator
    ) {
        return $this->indexResource($rest, $resources, $selector, $operator);
    }

    /**
     * Get a list of the resource.
     *
     * @param  \Peakfijn\GetSomeRest\Contracts\Anatomy $rest
     * @param  \Peakfijn\GetSomeRest\Contracts\ResourceFactory $resources
     * @param  \Peakfijn\GetSomeRest\Contracts\Selector $selector
     * @param  \Peakfijn\GetSomeRest\Contracts\Operator $operator
     * @return mixed
     */
    protected function indexResource(
        AnatomyContract $rest,
        ResourceFactoryContract $resources,
        SelectorContract $selector,
        OperatorContract $operator
    ) {
        $resource = $resources->make($rest);

        $resource = $selector->filter($resource);
        $resource = $operator->execute($resource);

        return $resource->get();
    }
}
