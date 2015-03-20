<?php namespace Peakfijn\GetSomeRest\Http\Controllers;

trait ResourceShowTrait
{
    /**
     * Display the specified resource.
     *
     * @return mixed
     */
    public function show()
    {
        return $this->showResource();
    }

    /**
     * Get a single resource, by it's primary key.
     *
     * @return mixed
     */
    protected function showResource()
    {
        $request = app('Peakfijn\GetSomeRest\Http\Request');
        $resource = $request->resource();

        event($request->resourceEventName(), [$resource]);

        return $resource;
    }
}
