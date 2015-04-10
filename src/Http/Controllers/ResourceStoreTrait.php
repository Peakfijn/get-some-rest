<?php namespace Peakfijn\GetSomeRest\Http\Controllers;

use Illuminate\Http\Request;
use Peakfijn\GetSomeRest\Contracts\Factories\ResourceFactory as ResourceFactoryContract;
use Peakfijn\GetSomeRest\Contracts\Rest\Anatomy as AnatomyContract;
use Peakfijn\GetSomeRest\Exceptions\ResourceSaveException;


trait ResourceStoreTrait
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Peakfijn\GetSomeRest\Contracts\Anatomy $rest
     * @param  \Peakfijn\GetSomeRest\Contracts\ResourceFactory $resources
     * @param  \Illuminate\Http\Request $request
     * @return mixed
     */
    public function store(
        AnatomyContract $rest,
        ResourceFactoryContract $resources,
        Request $request
    ) {
        return $this->storeResource($rest, $resources, $request->input());
    }

    /**
     * Store a new resource in storage.
     *
     * @throws \Peakfijn\GetSomeRest\Exceptions\ResourceSaveException
     * @param  \Peakfijn\GetSomeRest\Contracts\Anatomy $rest
     * @param  \Peakfijn\GetSomeRest\Contracts\ResourceFactory $resources
     * @param  array $input (default: [])
     * @return mixed
     */
    protected function storeResource(
        AnatomyContract $rest,
        ResourceFactoryContract $resources,
        array $input = array()
    ) {
        $resource = $resources->make($rest);
        $resource->fill($input);

        if (!$resource->save()) {
            throw new ResourceSaveException();
        }

        return $resource;
    }
}
