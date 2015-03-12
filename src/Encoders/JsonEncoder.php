<?php namespace Peakfijn\GetSomeRest\Encoders;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Peakfijn\GetSomeRest\Contracts\Encoder;

class JsonEncoder implements Encoder
{
    /**
     * Get the data from the response and morph it to a string.
     *
     * @param  \Peakfijn\GetSomeRest\Http\Request  $request
     * @param  \Peakfijn\GetSomeRest\Http\Response $response
     * @return \Peakfijn\GetSomeRest\Http\Response
     */
    public function encode(Request $request, Response $response)
    {
        $json = json_encode($response->getOriginalContent());

        $response->setContent($json);

        return $response;
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
