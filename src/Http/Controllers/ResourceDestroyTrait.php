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
        return $this->getResourceDestroy();
    }

    /**
     * Remove the specified resource from the storage, and return the removed entity.
     *
     * @throws \Peakfijn\GetSomeRest\Http\Exceptions\ResourceDestroyException
     * @return mixed
     */
    protected function getResourceDestroy()
    {
        $resource = app('Peakfijn\GetSomeRest\Http\Request')->resource();

        if ($resource->delete()) {
            return $resource;
        }

        throw new ResourceDestroyException();
    }
}
