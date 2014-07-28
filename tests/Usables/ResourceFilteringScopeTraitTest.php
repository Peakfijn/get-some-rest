<?php

use Bycedric\Inquiry\Inquiry;
use Bycedric\Inquiry\Facades\Inquiry as InquiryFacade;

class ResourceFilteringScopeTraitTest extends TestCase {

	/**
	 * Mock the Inquiry library.
	 *
	 * @param  string $key
	 * @param  string $value
	 * @return \Mockery\Mock
	 */
	protected function setInquiryMock( $key, $value )
	{
		return InquiryFacade::shouldReceive('get')
			->andReturn(new Inquiry($key, $value));
	}

	/**
	 * Clear the possible mocked facades of the Inquiry library.
	 * 
	 * @return void
	 */
	public function tearDown()
	{
		parent::tearDown();

		InquiryFacade::clearResolvedInstances();
	}

	/**
	 * Test if a simple attribute can be filtered.
	 * 
	 * @return void
	 */
	public function testResourceAttributeCanBeFiltered()
	{
		$this->setInquiryMock('id', 3);
		$mock = Mockery::mock('Query');
		
		$mock
			->shouldReceive('where')
			->once()
			->andReturn(null);

		(new ResourceFilteringScopeStub())->scopeFilter($mock, ['id' => 3]);//, 'name' => 'Cedric']);
	}

	/**
	 * Test if an unknown attribute is ignored.
	 * 
	 * @return void
	 */
	public function testResourceUnknownAttributeIsNotFiltered()
	{
		$this->setInquiryMock('unknown', 'empty');
		$mock = Mockery::mock('Query');

		(new ResourceFilteringScopeStub())->scopeFilter($mock, ['unknown' => 'empty']);
	}

	/**
	 * Test if the equal operator is detected and handled
	 * 
	 * @return void
	 */
	public function testOperatorEqualIsDetectedAndHandled()
	{
		$this->setInquiryMock('id', '=3');
		$mock = Mockery::mock('Query');
		
		$mock
			->shouldReceive('where')
			->with('id', '=', 3)
			->once()
			->andReturn(null);

		(new ResourceFilteringScopeStub())->scopeFilter($mock, ['id' => '=3']);
	}

	/**
	 * Test if the bigger than operator is detected and handled
	 * 
	 * @return void
	 */
	public function testOperatorBiggerThanIsDetectedAndHandled()
	{
		$this->setInquiryMock('id', ']3');
		$mock = Mockery::mock('Query');
		
		$mock
			->shouldReceive('where')
			->with('id', '>', 3)
			->once()
			->andReturn(null);

		(new ResourceFilteringScopeStub())->scopeFilter($mock, ['id' => ']3']);
	}

	/**
	 * Test if the smaller than operator is detected and handled
	 * 
	 * @return void
	 */
	public function testOperatorSmallerThanIsDetectedAndHandled()
	{
		$this->setInquiryMock('id', '[3');
		$mock = Mockery::mock('Query');
		
		$mock
			->shouldReceive('where')
			->with('id', '<', 3)
			->once()
			->andReturn(null);

		(new ResourceFilteringScopeStub())->scopeFilter($mock, ['id' => '[3']);
	}

	/**
	 * Test if the like operator is detected and handled
	 * 
	 * @return void
	 */
	public function testOperatorLikeIsDetectedAndHandled()
	{
		$this->setInquiryMock('name', '~abc');
		$mock = Mockery::mock('Query');
		
		$mock
			->shouldReceive('where')
			->with('name', 'LIKE', '%abc%')
			->once()
			->andReturn(null);

		(new ResourceFilteringScopeStub())->scopeFilter($mock, ['name' => '~abc']);
	}

	/**
	 * Test if a simple relation can be filtered.
	 * 
	 * @return void
	 */
	public function testResourceRelationCanBeFiltered()
	{
		$this->setInquiryMock('fake_relation:name', 'right-hand');
		$mock = Mockery::mock('Query');
		
		$mock
			->shouldReceive('where')
			->once()
			->andReturn(null);

		(new ResourceFilteringScopeStub())->scopeFilter(
			new ResourceQueryStub($mock),
			['fake_relation-name' => 'right-hand']
		);
	}

	/**
	 * Test if an unknown relation is ignored.
	 * 
	 * @return void
	 */
	public function testResourceUnknownRelationIsNotFiltered()
	{
		$this->setInquiryMock('unknown_relation:name', 'someone');
		$mock = Mockery::mock('Query');

		(new ResourceFilteringScopeStub())->scopeFilter(
			new ResourceQueryStub($mock),
			['unknown_relation-name' => 'someone']
		);
	}

	/**
	 * Test if the equal operator is detected and handled on relation
	 * 
	 * @return void
	 */
	public function testOperatorEqualIsDetectedAndHandledOnRelation()
	{
		$this->setInquiryMock('fake_relation:id', '=3');
		$mock = Mockery::mock('Query');
		
		$mock
			->shouldReceive('where')
			->with('id', '=', 3)
			->once()
			->andReturn(null);

		(new ResourceFilteringScopeStub())->scopeFilter(
			new ResourceQueryStub($mock),
			['fake_relation:id' => '=3']
		);
	}

	/**
	 * Test if the bigger than operator is detected and handled on relation
	 * 
	 * @return void
	 */
	public function testOperatorBiggerThanIsDetectedAndHandledOnRelation()
	{
		$this->setInquiryMock('fake_relation:id', ']3');
		$mock = Mockery::mock('Query');
		
		$mock
			->shouldReceive('where')
			->with('id', '>', 3)
			->once()
			->andReturn(null);

		(new ResourceFilteringScopeStub())->scopeFilter(
			new ResourceQueryStub($mock),
			['fake_relation:id' => ']3']
		);
	}

	/**
	 * Test if the smaller than operator is detected and handled on relation
	 * 
	 * @return void
	 */
	public function testOperatorSmallerThanIsDetectedAndHandledOnRelation()
	{
		$this->setInquiryMock('fake_relation:id', '[3');
		$mock = Mockery::mock('Query');
		
		$mock
			->shouldReceive('where')
			->with('id', '<', 3)
			->once()
			->andReturn(null);

		(new ResourceFilteringScopeStub())->scopeFilter(
			new ResourceQueryStub($mock),
			['fake_relation:id' => '[3']
		);
	}

	/**
	 * Test if the like operator is detected and handled on relation
	 * 
	 * @return void
	 */
	public function testOperatorLikeIsDetectedAndHandledOnRelation()
	{
		$this->setInquiryMock('fake_relation:name', '~abc');
		$mock = Mockery::mock('Query');
		
		$mock
			->shouldReceive('where')
			->with('name', 'LIKE', '%abc%')
			->once()
			->andReturn(null);

		(new ResourceFilteringScopeStub())->scopeFilter(
			new ResourceQueryStub($mock),
			['fake_relation:name' => '~abc']
		);
	}

}
