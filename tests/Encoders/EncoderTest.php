<?php namespace Peakfijn\GetSomeRest\Tests\Encoders;

use Mockery;
use Peakfijn\GetSomeRest\Tests\AbstractUnitTest;

abstract class EncoderTest extends AbstractUnitTest
{
    /**
     * Get a mocked request for testing.
     *
     * @return \Mockery\Mock
     */
    public function getMockedRequest()
    {
        return Mockery::mock('\Illuminate\Http\Request');
    }

    public function testGetContentTypeReturnsString()
    {
        $encoder = $this->getInstance();

        $type = $encoder->getContentType();

        $this->assertInternalType('string', $type);
        $this->assertNotEmpty($type);
    }

    public function testEncodeReturnsString()
    {
        $encoder = $this->getInstance();
        $request = $this->getMockedRequest();
        $content = ['test' => true];

        $encoded = $encoder->encode($request, $content);

        $this->assertInternalType('string', $encoded);
        $this->assertNotEmpty($encoded);
    }
}
