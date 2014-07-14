<?php

use Peakfijn\GetSomeRest\Http\Response;
use Peakfijn\GetSomeRest\Http\Pagination;

class PaginationTest extends TestCase {

	/**
	 * When creating a Pagination object, you should provide all pagination data.
	 * It's not possible to adjust it afterwards, because of security reasons.
	 * 
	 * @return void
	 */
	public function testInstantiatingSetsCountLimitAndOffset()
	{
		$pagination = new Pagination(30, 50, 0);

		$this->assertEquals(30, $pagination->getCount());
		$this->assertEquals(50, $pagination->getLimit());
		$this->assertEquals(0,  $pagination->getOffset());
	}

	/**
	 * When wanting to respond, it can be useful to create a response from the pagination.
	 * This way it will create a response with only 1 line.
	 * 
	 * @return void
	 */
	public function testCreatesResponseFromData()
	{
		$data     = ['this' => 'isData'];
		$response = Pagination::make($data, 50, 100, 25);

		$this->assertInstanceOf('\Peakfijn\GetSomeRest\Http\Response', $response);
		$this->assertInstanceOf('\Peakfijn\GetSomeRest\Http\Pagination', $response->getPagination());
	}

	/**
	 * It can be possible that a response was created, but some pagination was not added.
	 * So that's why it can be added later to the response.
	 * It should be added before the ->finalize() execution.
	 * 
	 * @return void
	 */
	public function testCreatesResponseFromExistingResponse()
	{
		$existing = new Response(['this' => 'isData']);
		$response = Pagination::makeFromResponse($existing, 75, 150, 50);

		$this->assertInstanceOf('\Peakfijn\GetSomeRest\Http\Response', $response);
		$this->assertInstanceOf('\Peakfijn\GetSomeRest\Http\Pagination', $response->getPagination());
	}

}