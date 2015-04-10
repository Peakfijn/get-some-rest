<?php namespace Peakfijn\GetSomeRest\Tests\Encoders;

use Peakfijn\GetSomeRest\Encoders\JsonEncoder;

class JsonEncoderTest extends EncoderTest
{
    /**
     * Get a new instance of the relevant class.
     * All possible parameters MUST be optional.
     *
     * @return \Peakfijn\GetSomeRest\Encoders\JsonEncoder
     */
    protected function getInstance()
    {
        return new JsonEncoder();
    }

    public function testGetContentTypeContainsJson()
    {
        $encoder = $this->getInstance();

        $this->assertRegexp('/json/', $encoder->getContentType());
    }
}
