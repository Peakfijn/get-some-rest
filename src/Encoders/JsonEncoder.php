<?php namespace Peakfijn\GetSomeRest\Encoders;

use Peakfijn\GetSomeRest\Contracts\Encoder;
use Peakfijn\GetSomeRest\Http\Response;

class JsonEncoder extends Encoder {

    /**
     * Get the encoded content type.
     *
     * @return string
     */
    public function getContentType()
    {
        return 'application/json';
    }

    /**
     * Get the encoded content.
     *
     * @param Response $response
     * @return string
     */
    public function getContent(Response $response)
    {
        $data = $response->getOriginalContent();

        return json_encode($data);
    }

}