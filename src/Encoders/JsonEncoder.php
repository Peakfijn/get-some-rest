<?php namespace Peakfijn\GetSomeRest\Encoders;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Peakfijn\GetSomeRest\Contracts\Encoder;

class JsonEncoder implements Encoder
{
    /**
     * Modify the provided response, so the content will be encoded in the
     * desired encoding.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response $response
     * @return \Illuminate\Http\Response
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
