<?php namespace Peakfijn\GetSomeRest\Encoders;

use Symfony\Component\HttpFoundation\Request;
use Peakfijn\GetSomeRest\Contracts\Encoder;

class JsonEncoder implements Encoder
{
    /**
     * Modify the provided response, so the content will be encoded in the
     * desired encoding.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  mixed                    $data
     * @return string
     */
    public function encode(Request $request, $data)
    {
        return json_encode((array) $data);
    }

    /**
     * Get the content type for this encoder.
     * This should be a valid mime type string.
     *
     * @see    http://www.iana.org/assignments/media-types/media-types.xhtml
     * @return string
     */
    public function getContentType()
    {
        return 'application/json';
    }
}
