<?php namespace Peakfijn\GetSomeRest\Rest;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Peakfijn\GetSomeRest\Contracts\Rest\Dissector as DissectorContract;
use Peakfijn\GetSomeRest\Contracts\Rest\Selector as SelectorContract;

class Selector implements SelectorContract
{
    /**
     * The dissector to extract from.
     *
     * @var \Peakfijn\GetSomeRest\Contracts\Rest\Dissector
     */
    protected $dissector;

    /**
     * Create a new selector.
     *
     * @param \Peakfijn\GetSomeRest\Contracts\Rest\Dissector $dissector
     */
    public function __construct(DissectorContract $dissector)
    {
        $this->dissector = $dissector;
    }

    /**
     * Execute the filter on the provided query.
     *
     * @param  mixed $resource
     * @return mixed
     */
    public function filter($resource)
    {
        $filters = $this->dissector->filters();

        foreach ($filters as $attribute => $filter) {
            if ($this->isFilterableAttribute($resource, $attribute)) {
                $resource = $resource->whereIn($attribute, (array)$filter);
            }
        }

        return $resource;
    }

    /**
     * Check if the requested filter is filterable.
     *
     * @param  mixed $resource
     * @param  string $attribute
     * @return boolean
     */
    public function isFilterableAttribute($resource, $attribute)
    {
        return in_array($attribute, $this->getModel($resource)->getVisible());
    }

    /**
     * Try and extract the model itself.
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
