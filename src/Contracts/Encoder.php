<?php namespace Peakfijn\GetSomeRest\Contracts;

use Symfony\Component\HttpFoundation\Request;

interface Encoder
{
    /**
     * Encode the data, and return the encoded string.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  mixed                    $data
     * @return string
     */
    public function encode(Request $request, $data);

    /**
     * Get the content type for this encoder.
     * This should be a valid mime type string.
     *
     * @see    http://www.iana.org/assignments/media-types/media-types.xhtml
     * @return string
     */
    public function getContentType();
}
