<?php namespace Peakfijn\GetSomeRest\Contracts\Rest;

interface Dissector
{
    /**
     * Dissect the REST information from the request.
     *
     * @return \Peakfijn\GetSomeRest\Contracts\Rest\Anatomy
     */
    public function anatomy();

    /**
     * Dissect the requested REST methods.
     *
     * @param  boolean $removePrefix (default: true)
     * @return array
     */
    public function methods($removePrefix = true);

    /**
     * Dissect the requested REST filters.
     *
     * @return mixed
     */
    public function filters();
}
