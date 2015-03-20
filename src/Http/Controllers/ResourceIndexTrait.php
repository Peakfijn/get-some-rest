<?php namespace Peakfijn\GetSomeRest\Http\Controllers;

trait ResourceIndexTrait
{
    /**
     * Display a listing of the resource.
     *
     * @return mixed
     */
    public function index()
    {
        return $this->indexResource();
    }

    /**
     * Get a list of the resource.
     *
     * @return mixed
     */
    protected function indexResource()
    {
        $request = app('Peakfijn\GetSomeRest\Http\Request');
        $resources = $request->resource()->get();

        event($request->resourceEventName(), [$resources]);

        return $resources;
    }
}
