<?php

use Illuminate\Http\Request;
use Illuminate\Events\Dispatcher;
use Peakfijn\GetSomeRest\Http\Response;
use Peakfijn\GetSomeRest\Routing\Router;
use Peakfijn\GetSomeRest\Exceptions\NotFoundResourceException;

class RouterTest extends TestCase {

	/**
	 * Setup a work router for each test.
	 *
	 * @return void
	 */
	public function setUp()
	{
		$this->router = new Router(new Dispatcher());		
		$this->router->mutators = ['plain' => '\Peakfijn\GetSomeRest\Mutators\PlainMutator'];
		$this->router->encoders = [
			'json' => '\Peakfijn\GetSomeRest\Encoders\JsonEncoder',
			'xml'  => '\Peakfijn\GetSomeRest\Encoders\XmlEncoder',
		];
		$this->router->extensionAliases = ['jsonon' => 'json'];
	}

	/**
	 * Test if the Router::API function actually creates the routes using only a string
	 * to define the version.
	 * 
	 * @return void
	 */
	public function testRegisterApiRoutesUsingStringAsSettings()
	{
		$this->router->api('v1', function(){
			$this->router->get('foo', function(){ return 'bar'; });
		});

		$request = Request::create('/v1/foo', 'GET');
		$response = $this->router->dispatch($request);

		$this->assertInstanceOf('\Peakfijn\GetSomeRest\Http\Response', $response);
		$this->assertEquals(200, $response->getStatusCode());
		$this->assertEquals(json_encode(['bar']), $response->getOriginalContent());
	}
	
	/**
	 * Test if the Router::API function actually creates the routes using only an array
	 * to define the version.
	 * 
	 * @return void
	 */
	public function testRegisterApiRoutesUsingArrayAsSettings()
	{
		$this->router->api(['version' => 'v1'], function(){
			$this->router->get('foo', function(){ return 'bar'; });
		});

		$request = Request::create('/v1/foo', 'GET');
		$response = $this->router->dispatch($request);

		$this->assertInstanceOf('\Peakfijn\GetSomeRest\Http\Response', $response);
		$this->assertEquals(200, $response->getStatusCode());
		$this->assertEquals(json_encode(['bar']), $response->getOriginalContent());
	}

	/**
	 * Test if the Router::API function actually creates the routes using only an
	 * array, with prefix, to define the version.
	 * 
	 * @return void
	 */
	public function testRegisterApiRoutesUsingArrayAndPrefixAsSettings()
	{
		$this->router->api(['version' => 'v1', 'prefix' => 'api'], function(){
			$this->router->get('foo', function(){ return 'bar'; });
		});

		$request = Request::create('/api/v1/foo', 'GET');
		$response = $this->router->dispatch($request);

		$this->assertInstanceOf('\Peakfijn\GetSomeRest\Http\Response', $response);
		$this->assertEquals(200, $response->getStatusCode());
		$this->assertEquals(json_encode(['bar']), $response->getOriginalContent());
	}

	/**
	 * Test if an thrown exception is handled and a nice response is returned.
	 * 
	 * @return void
	 */
	public function testExceptionIsHandledAndReturnsAsResponse()
	{
		$this->router->api('v1', function(){
			$this->router->get('foo', function(){ throw new NotFoundResourceException('bar'); });
		});

		$request = Request::create('/v1/foo', 'GET');
		$response = $this->router->dispatch($request);

		$this->assertInstanceOf('\Peakfijn\GetSomeRest\Http\Response', $response);
		$this->assertEquals(404, $response->getStatusCode());
	}

	/**
	 * Only system exceptions should not be handled by the Router.
	 * 
	 * @expectedException RuntimeException
	 * @return void
	 */
	public function testExceptionIsNotHandledWhenASystemExceptionWasThrown()
	{
		$this->router->api('v1', function(){
			$this->router->get('foo', function(){ throw new RuntimeException(); });
		});

		$request = Request::create('/v1/foo', 'GET');
		$response = $this->router->dispatch($request);
	}

	/**
	 * Test if the http status code is changed for normal responses, based on the http method.
	 * 
	 * @return void
	 */
	public function testResponsePreparalSetsStatusCodeBasedOnHttpMethod()
	{
		$data   = ['test' => true];

		$get    = Request::create('/v1/foo', 'GET');
		$post   = Request::create('/v1/foo', 'POST');
		$put    = Request::create('/v1/foo', 'PATCH');
		$delete = Request::create('/v1/foo', 'DELETE');

		$get_response    = $this->invokeMethod($this->router, 'prepareResponse', [$get, $data]);
		$post_response   = $this->invokeMethod($this->router, 'prepareResponse', [$post, $data]);
		$put_response    = $this->invokeMethod($this->router, 'prepareResponse', [$put, $data]);
		$delete_response = $this->invokeMethod($this->router, 'prepareResponse', [$delete, $data]);

		$this->assertEquals(200, $get_response->getStatusCode());
		$this->assertEquals(201, $post_response->getStatusCode());
		$this->assertEquals(204, $put_response->getStatusCode());
		$this->assertEquals(204, $delete_response->getStatusCode());
	}

	/**
	 * Test if the http status code is used from the predefined response.
	 * 
	 * @return void
	 */
	public function testResponsePreparalSetsStatusCodeOfGivenResponseObject()
	{
		$request  =  Request::create('/v1/foo', 'GET');
		$response = Response::create('test', 204);

		$prepared = $this->invokeMethod($this->router, 'prepareResponse', [$request, $response]);

		$this->assertEquals($response->getStatusCode(), $prepared->getStatusCode());
	}

	/**
	 * Test if the default resourceful methods are changed within an API group.
	 * 
	 * @return void
	 */
	public function testResourceMethodsRetrievalReturnsApiMethodsOnApiGroup()
	{
		$router = Mockery::mock('\Peakfijn\GetSomeRest\Routing\Router[isApiLastGroup]', [new Dispatcher()]);
		$router
			->shouldAllowMockingProtectedMethods()
			->shouldReceive('isApiLastGroup')
			->andReturn(true);

		$normal    = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'];
		$modified  = ['index',           'store', 'show',         'update', 'destroy'];
		$methods   = $this->invokeMethod($router, 'getResourceMethods', [$normal, []]);

		$this->assertEquals(array_values($modified), array_values($methods));
	}

	/**
	 * Test if the default resourceful methods are NOT changed outside an API group.
	 * 
	 * @return void
	 */
	public function testResourceMethodsRetrievalReturnsNormalResourceMethodsOnNonApiGroup()
	{
		$normal  = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'];
		$methods = $this->invokeMethod($this->router, 'getResourceMethods', [$normal, []]);

		$this->assertEquals(array_values($normal), array_values($methods));
	}
	
	/**
	 * Test if the router can detect an normal API call.
	 * 
	 * @return void
	 */
	public function testApiRequestCheckReturnsTrueOnApiRequest()
	{
		$request = Request::create('/v1/foo', 'GET');
		$is_api  = $this->invokeMethod($this->router, 'isApiRequest', [$request]);

		$this->assertTrue($is_api);
	}

	/**
	 * Test if the router can detect an normal API call.
	 * 
	 * @return void
	 */
	public function testApiRequestCheckReturnsTrueOnApiRequestWithPrefix()
	{
		$request = Request::create('/api/v1/foo', 'GET');
		$is_api  = $this->invokeMethod($this->router, 'isApiRequest', [$request]);

		$this->assertTrue($is_api);
	}

	/**
	 * Test if the router can also detect a non-api call.
	 * 
	 * @return void
	 */
	public function testApiRequestCheckReturnsFalseOnNonApiRequest()
	{
		$request = Request::create('/home', 'GET');
		$is_api  = $this->invokeMethod($this->router, 'isApiRequest', [$request]);

		$this->assertFalse($is_api);
	}

	/**
	 * Test if the encoder retrieval returns an instance of an encoder.
	 * 
	 * @return void
	 */
	public function testEncoderRetrievalReturnsEncoderInstance()
	{
		$request = Request::create('/v1/foo', 'GET');
		$encoder = $this->invokeMethod($this->router, 'getEncoder', [$request]);

		$this->assertInstanceOf('\Peakfijn\GetSomeRest\Contracts\Encoder', $encoder);
	}

	/**
	 * Test if the encoder retrieval retrieves an encoder, based on the url extension.
	 * 
	 * @return void
	 */
	public function testEncoderRetrievalSearchesEncoderBasedOnUrlExtension()
	{
		$request = Request::create('/v1/foo.xml', 'GET');
		$encoder = $this->invokeMethod($this->router, 'getEncoder', [$request]);

		$this->assertInstanceOf('\Peakfijn\GetSomeRest\Contracts\Encoder', $encoder);
	}

	/**
	 * Test if the encoder retrieval retrieves an encoder, based on the url extension.
	 * Also using aliases.
	 * 
	 * @return void
	 */
	public function testEncoderRetrievalSearchesEncoderBasedOnUrlExtensionUsingAliases()
	{
		$request = Request::create('/v1/foo.jsonon', 'GET');
		$encoder = $this->invokeMethod($this->router, 'getEncoder', [$request]);

		$this->assertInstanceOf('\Peakfijn\GetSomeRest\Contracts\Encoder', $encoder);
	}

	/**
	 * Test if the encoder retrieval throws an exception if an unknown encoder is requested.
	 *
	 * @expectedException RuntimeException
	 * @return void
	 */
	public function testEncoderRetrievalThrowsExceptionOnUnknownEncoder()
	{
		$this->router->failUnsupportedEncoder = true;

		$request = Request::create('/v1/foo.thisisanunknownformat', 'GET');
		$encoder = $this->invokeMethod($this->router, 'getEncoder', [$request]);
	}

	/**
	 * Test if the mutator retrieval returns an instance of a mutator.
	 * 
	 * @return void
	 */
	public function testMutatorRetrievalReturnsMutatorInstance()
	{
		$request = Request::create('/v1/foo', 'GET');
		$encoder = $this->invokeMethod($this->router, 'getMutator', [$request]);

		$this->assertInstanceOf('\Peakfijn\GetSomeRest\Contracts\Mutator', $encoder);
	}

}
