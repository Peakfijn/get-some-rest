<?php namespace Peakfijn\GetSomeRest\Http\Controllers;

use Peakfijn\GetSomeRest\Contracts\Factories\ResourceFactory as ResourceFactoryContract;
use Peakfijn\GetSomeRest\Contracts\Rest\Anatomy as AnatomyContract;
use Peakfijn\GetSomeRest\Exceptions\ResourceDestroyException;

trait ResourceDestroyTrait
{
    /**
     * Remove the specified resource from storage.
     *
     * @param  \Peakfijn\GetSomeRest\Contracts\Rest\Anatomy $rest
     * @param  \Peakfijn\GetSomeRest\Contracts\Factories\ResourceFactory $resources
     * @return mixed
     */
    public function destroy(
        AnatomyContract $rest,
        ResourceFactoryContract $resources
    ) {
        return $this->destroyResource($rest, $resources);
    }

    /**
     * Remove the specified resource from the storage, and return the removed entity.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \Peakfijn\GetSomeRest\Exceptions\ResourceDestroyException
     * @param  \Peakfijn\GetSomeRest\Contracts\Rest\Anatomy $rest
     * @param  \Peakfijn\GetSomeRest\Contracts\Factories\ResourceFactory $resources
     * @return mixed
     */
    protected function destroyResource(
        AnatomyContract $rest,
        ResourceFactoryContract $resources
    ) {
        $resource = $resources->make($rest)
            ->findOrFail($rest->getResourceId());

        if (!$resource->delete()) {
            throw new ResourceDestroyException();
        }

        return $resource;
    }
}
