<?php namespace Peakfijn\GetSomeRest\Tests\Http;

use RuntimeException;
use Mockery;
use StdClass;
use Peakfijn\GetSomeRest\Http\Response;
use Peakfijn\GetSomeRest\Tests\AbstractUnitTest;

class ResponseTest extends AbstractUnitTest
{
    /**
     * Get a new instance of the relevant class.
     * All possible parameters MUST be optional.
     *
     * @return \Peakfijn\GetSomeRest\Http\Response
     */
    protected function getInstance($content = '')
    {
        return new Response($content);
    }

    /**
     * Get a mocked object of the getInstance object's class.
     * It will generate a partial mock.
     *
     * @return \Mockery\Mock
     */
    protected function getMockedInstance()
    {
        $mock = Mockery::mock('\Peakfijn\GetSomeRest\Http\Response');
        $mock->headers = $this->getMockedBag();

        return $mock;
    }

    /**
     * Get a mocked encoder for testing.
     *
     * @return \Mockery\Mock
     */
    protected function getMockedEncoder()
    {
        return Mockery::mock('\Peakfijn\GetSomeRest\Contracts\Encoders\Encoder');
    }

    /**
     * Get a mocked mutator for testing.
     *
     * @return \Mockery\Mock
     */
    protected function getMockedMutator()
    {
        return Mockery::mock('\Peakfijn\GetSomeRest\Contracts\Mutators\Mutator');
    }

    /**
     * Get a mocked request for testing.
     *
     * @return \Mockery\Mock
     */
    protected function getMockedRequest()
    {
        $mock = Mockery::mock('\Illuminate\Http\Request');

        $mock->shouldReceive('getRequestFormat');

        $mock->shouldReceive('isMethod')
            ->andReturn(false);

        $mock->server = $this->getMockedBag();

        return $mock;
    }

    /**
     * Get a mocked bag for testing.
     *
     * @return \Mockery\Mock
     */
    protected function getMockedBag()
    {
        $mock = Mockery::mock('\Symfony\Component\HttpFoundation\ResponseHeaderBag');

        $mock->shouldReceive('set');

        $mock->shouldReceive('get')
            ->andReturn(false);

        $mock->shouldReceive('has')
            ->andReturn(false);

        $mock->shouldReceive('all')
            ->andReturn([]);

        return $mock;
    }

    /**
     * Get a mocked illuminate response for testing.
     *
     * @return \Mockery\Mock
     */
    protected function getMockedIlluminateResponse()
    {
        return Mockery::mock('\Illuminate\Http\Response');
    }

    /**
     * Get a mocked symfony response for testing.
     *
     * @return \Mockery\Mock
     */
    protected function getMockedSymfonyResponse()
    {
        return Mockery::mock('\Symfony\Component\HttpFoundation\Response');
    }

    public function testConstructSetsOriginalContent()
    {
        $response = $this->getInstance('test');
        $original = $this->getProtectedProperty($response, 'original');

        $this->assertEquals('test', $original);
    }

    public function testSetOriginalContentSavesContentAndReturnsResponse()
    {
        $response = $this->getInstance();

        $original = new StdClass();
        $original->test = true;

        $this->assertEquals($response, $response->setOriginalContent($original));
        $this->assertEquals($original, $response->getOriginalContent());
    }

    public function testSetEncoderSavesEncoderAndReturnsResponse()
    {
        $response = $this->getInstance();
        $encoder = $this->getMockedEncoder();

        $this->assertEquals($response, $response->setEncoder($encoder));
        $this->assertEquals($encoder, $response->getEncoder());
    }

    public function testSetEncoderOnlyAllowsEncoders()
    {
        $response = $this->getInstance();

        try {
            $response->setEncoder(new StdClass());
        } catch (RuntimeException $e) {
            return;
        }

        $this->fail('The response allowed a non-encoder to be set as encoder.');
    }

    public function testSetMutatorSavesMutatorAndReturnsResponse()
    {
        $response = $this->getInstance();
        $mutator = $this->getMockedMutator();

        $this->assertEquals($response, $response->setMutator($mutator));
        $this->assertEquals($mutator, $response->getMutator());
    }

    public function testSetMutatorOnlyAllowsMutators()
    {
        $response = $this->getInstance();

        try {
            $response->setMutator(new StdClass());
        } catch (RuntimeException $e) {
            return;
        }

        $this->fail('The response allowed a non-mutator to be set as mutator.');
    }

    public function testPrepareCallsMutatorAndEncoder()
    {
        $response = $this->getMockedInstance()->makePartial();
        $request  = $this->getMockedRequest();
        $mutator  = $this->getMockedMutator();
        $encoder  = $this->getMockedEncoder();

        $content = ['test' => true];
        $encoded = json_encode($content);

        $mutator->shouldReceive('mutate')
            ->with($request, 200, $content)
            ->atLeast()->once()
            ->andReturn($content);

        $encoder->shouldReceive('encode')
            ->with($request, $content)
            ->atLeast()->once()
            ->andReturn($encoded);

        $encoder->shouldReceive('getContentType')
            ->atLeast()->once()
            ->andReturn('application/json');

        $response->shouldReceive('getOriginalContent')
            ->atLeast()->once()
            ->andReturn($content);

        $response->shouldReceive('setContent')
            ->with($encoded)
            ->atLeast()->once();

        $response->shouldReceive('getMutator')
            ->atLeast()->once()
            ->andReturn($mutator);

        $response->shouldReceive('getEncoder')
            ->atLeast()->once()
            ->andReturn($encoder);

        $response->shouldReceive('getStatusCode')
            ->atLeast()->once()
            ->andReturn(200);

        $response->prepare($request);
    }

    public function testMakeFromExistingExtractsAllData()
    {
        $response = $this->getInstance();
        $content  = ['test' => true];

        $peakfijnResponse   = $this->getMockedInstance();
        $symfonyResponse    = $this->getMockedSymfonyResponse();
        $illuminateResponse = $this->getMockedIlluminateResponse();

        $symfonyResponse->headers = $this->getMockedBag();
        $illuminateResponse->headers = $this->getMockedBag();

        $symfonyResponse->shouldReceive('getContent')
            ->atLeast()->once()
            ->andReturn($content);

        $symfonyResponse->shouldReceive('getStatusCode')
            ->atLeast()->once()
            ->andReturn(200);

        $illuminateResponse->shouldReceive('getContent')
            ->once()
            ->andReturn($content);

        $illuminateResponse->shouldReceive('getOriginalContent')
            ->atLeast()->once()
            ->andReturn($content);

        $illuminateResponse->shouldReceive('getStatusCode')
            ->atLeast()->once()
            ->andReturn(200);

        $this->assertEquals($peakfijnResponse, $response::makeFromExisting($peakfijnResponse));
        $this->assertInstanceOf('\Peakfijn\GetSomeRest\Http\Response', $response::makeFromExisting($symfonyResponse));
        $this->assertInstanceOf('\Peakfijn\GetSomeRest\Http\Response', $response::makeFromExisting($illuminateResponse));
    }
}
