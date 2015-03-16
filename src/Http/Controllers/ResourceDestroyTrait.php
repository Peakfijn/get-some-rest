<?php namespace Peakfijn\GetSomeRest\Http\Controllers;

use Peakfijn\GetSomeRest\Http\Exceptions\ResourceDestroyException;
use Peakfijn\GetSomeRest\Http\Request;

trait ResourceDestroyTrait
{
    /**
     * Remove the specified resource from storage.
     *
     * @return mixed
     */
    public function destroy()
    {
        return $this->destroyResource();
    }

    /**
     * Remove the specified resource from the storage, and return the removed entity.
     *
     * @throws \Peakfijn\GetSomeRest\Http\Exceptions\ResourceDestroyException
     * @return mixed
     */
    protected function destroyResource()
    {
        $resource = app('Peakfijn\GetSomeRest\Http\Request')->resource();

        if (! $resource->delete()) {
            throw new ResourceDestroyException();
        }

        return $resource;
    }
}
