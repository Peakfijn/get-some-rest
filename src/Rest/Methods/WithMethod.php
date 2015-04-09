<?php namespace Peakfijn\GetSomeRest\Rest\Methods;

class WithMethod extends Method
{
    /**
     * Execute the method with the provided resource and method value.
     *
     * @param  mixed $resource
     * @param  mixed $resource
     * @return mixed
     */
    public function execute($value, $resource)
    {
        $relations = explode(',', $value);

        foreach ($relations as $relation) {
            if ($this->isVisibleAttribute($relation, $resource)) {
                $resource = $resource->with($relation);
            }
        }

        return $resource;
    }
}
