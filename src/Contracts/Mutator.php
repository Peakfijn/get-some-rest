<?php namespace Peakfijn\GetSomeRest\Contracts;

use Illuminate\Contracts\Support\Arrayable;
use Peakfijn\GetSomeRest\Http\Response;

abstract class Mutator {

    /**
     * Get the mutated content
     *
     * @param Response $response
     * @return array
     */
    public abstract function getContent(Response $response);

    /**
     * Try to convert the given data to an array.
     *
     * @param  mixed $data
     * @return array
     */
    protected function toArray($data)
    {
        if (is_array($data)) {
            return $data;
        }

        if ($data instanceof Arrayable) {
            return $data->toArray();
        }

        return (array)$data;
    }

}