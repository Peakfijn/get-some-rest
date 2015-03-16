<?php namespace Peakfijn\GetSomeRest\Http\Controllers;

use Peakfijn\GetSomeRest\Http\Exceptions\ResourceSaveException;
use Peakfijn\GetSomeRest\Http\Request;

trait ResourceStoreTrait
{
    /**
     * Store a newly created resource in storage.
     *
     * @return mixed
     */
    public function store()
    {
        return $this->storeResource();
    }

    /**
     * Store a new resource in storage.
     *
     * @throws \Peakfijn\GetSomeRest\Http\Exceptions\ResourceSaveException
     * @return mixed
     */
    protected function storeResource($resource = null)
    {
        if ($resource == null) {
            $request = app('Peakfijn\GetSomeRest\Http\Request');

            $resource = $request->resource();
            $resource->fill($request->input());
        }

        if (! $resource->save()) {
            throw new ResourceSaveException();
        }
        
        return $resource;

    }
}
