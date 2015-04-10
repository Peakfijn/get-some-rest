<?php namespace Peakfijn\GetSomeRest\Http\Controllers;

use \Illuminate\Http\Request;
use Peakfijn\GetSomeRest\Contracts\Factories\ResourceFactory as ResourceFactoryContract;
use Peakfijn\GetSomeRest\Contracts\Rest\Anatomy as AnatomyContract;
use Peakfijn\GetSomeRest\Exceptions\ResourceSaveException;

trait ResourceUpdateTrait
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Peakfijn\GetSomeRest\Contracts\Rest\Anatomy $rest
     * @param  \Peakfijn\GetSomeRest\Contracts\Factories\ResourceFactory $resources
     * @param  \Illuminate\Http\Request $request
     * @return mixed
     */
    public function update(
        AnatomyContract $rest,
        ResourceFactoryContract $resources,
        Request $request
    ) {
        return $this->updateResource($rest, $resources, $request->input());
    }

    /**
     * Update an existing resource in storage.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \Peakfijn\GetSomeRest\Exceptions\ResourceSaveException
     * @param  \Peakfijn\GetSomeRest\Contracts\Rest\Anatomy $rest
     * @param  \Peakfijn\GetSomeRest\Contracts\Factories\ResourceFactory $resources
     * @param  array $input (default: [])
     * @return mixed
     */
    protected function updateResource(
        AnatomyContract $rest,
        ResourceFactoryContract $resources,
        array $input = array()
    ) {
        $resource = $resources->make($rest)
            ->findOrFail($rest->getResourceId());

        $resource->fill($input);

        if (!$resource->save()) {
            throw new ResourceSaveException();
        }

        return $resource;
    }
}
