<?php namespace Peakfijn\GetSomeRest\Encoders;

use Illuminate\Http\Request;

class JsonEncoder extends Encoder
{
    /**
     * Encode the data, and return the encoded string.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  array $data
     * @return string
     */
    public function encode(Request $request, $data)
    {
        return json_encode((array)$data);
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
