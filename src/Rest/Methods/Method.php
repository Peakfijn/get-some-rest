<?php namespace Peakfijn\GetSomeRest\Rest\Methods;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Peakfijn\GetSomeRest\Contracts\Rest\Method as MethodContract;

abstract class Method implements MethodContract
{
    /**
     * Check if the provided attribute is visible.
     *
     * @param  mixed $attribute
     * @param  mixed $resource
     * @return boolean
     */
    protected function isVisibleAttribute($attribute, $resource)
    {
        return in_array($attribute, $this->getModel($resource)->getVisible());
    }

    /**
     * Try and extract the model itself from the resource query.
     *
     * @param  mixed $resource
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function getModel($resource)
    {
        if ($resource instanceof Builder) {
            return $resource->getModel();
        }

        if ($resource instanceof Relation) {
            return $resource->getRelated();
        }

        return $resource;
    }
}
