<?php namespace Peakfijn\GetSomeRest\Http\Controllers;

use Peakfijn\GetSomeRest\Contracts\Anatomy as AnatomyContract;
use Peakfijn\GetSomeRest\Contracts\ResourceFactory as ResourceFactoryContract;

trait ResourceIndexTrait
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Peakfijn\GetSomeRest\Contracts\Anatomy $rest
     * @param  \Peakfijn\GetSomeRest\Contracts\ResourceFactory $resources
     * @return mixed
     */
    public function index(
        AnatomyContract $rest,
        ResourceFactoryContract $resources
    ) {
        return $this->indexResource($rest, $resources);
    }

    /**
     * Get a list of the resource.
     *
     * @param  \Peakfijn\GetSomeRest\Contracts\Anatomy $rest
     * @param  \Peakfijn\GetSomeRest\Contracts\ResourceFactory $resources
     * @return mixed
     */
    protected function indexResource(
        AnatomyContract $rest,
        ResourceFactoryContract $resources
    ) {
        return $resources->make($rest)
            ->get();
    }
}
