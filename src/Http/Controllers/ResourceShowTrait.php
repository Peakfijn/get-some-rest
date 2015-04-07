<?php namespace Peakfijn\GetSomeRest\Http\Controllers;

use Peakfijn\GetSomeRest\Contracts\Anatomy as AnatomyContract;
use Peakfijn\GetSomeRest\Contracts\ResourceFactory as ResourceFactoryContract;

trait ResourceShowTrait
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Peakfijn\GetSomeRest\Contracts\Anatomy $rest
     * @param  \Peakfijn\GetSomeRest\Contracts\ResourceFactory $resources
     * @return mixed
     */
    public function show(
        AnatomyContract $rest,
        ResourceFactoryContract $resources
    ) {
        return $this->showResource($rest, $resources);
    }

    /**
     * Get a single resource, by it's primary key.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @param  \Peakfijn\GetSomeRest\Contracts\Anatomy $rest
     * @param  \Peakfijn\GetSomeRest\Contracts\ResourceFactory $resources
     * @return mixed
     */
    protected function showResource(
        AnatomyContract $rest,
        ResourceFactoryContract $resources
    ) {
        return $resources->make($rest)
            ->findOrFail($rest->getResourceId());
    }
}
