<?php namespace Peakfijn\GetSomeRest\Http\Controllers;

use Peakfijn\GetSomeRest\Http\Exceptions\ResourceSaveException;
use Peakfijn\GetSomeRest\Http\Request;

trait ResourceUpdateTrait
{
    /**
     * Update the specified resource in storage.
     *
     * @return mixed
     */
    public function update()
    {
        return $this->updateResource();
    }

    /**
     * Update an existing resource in storage.
     *
     * @throws \Peakfijn\GetSomeRest\Http\Exceptions\ResourceSaveException
     * @return mixed
     */
    protected function updateResource()
    {
        $request = app('Peakfijn\GetSomeRest\Http\Request');

        $resource = $request->resource();
        $resource->fill($request->input());

        if ($resource->save()) {
            return $resource;
        }

        throw new ResourceSaveException();
    }
}
