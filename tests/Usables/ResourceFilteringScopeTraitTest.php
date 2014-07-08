<?php

class ResourceFilteringScopeTraitTest extends TestCase {

	/**
	 * Test if a simple attribute can be filtered.
	 * 
	 * @return void
	 */
	public function testResourceAttributeCanBeFiltered()
	{
		$mock = Mockery::mock('Query');
		
		$mock
			->shouldReceive('where')
			->twice()
			->andReturn(null);

		(new ResourceFilteringScopeStub())->scopeFilter($mock, ['id' => 3, 'name' => 'Cedric']);
	}


	/**
	 * Test if an unknown attribute is ignored.
	 * 
	 * @return void
	 */
	public function testResourceUnknownAttributeIsNotFiltered()
	{
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
		$mock = Mockery::mock('Query');
		
		$mock
			->shouldReceive('where')
			->with('id', '>', 3)
			->once()
			->andReturn(null);

		(new ResourceFilteringScopeStub())->scopeFilter($mock, ['id' => '>3']);
	}

	/**
	 * Test if the smaller than operator is detected and handled
	 * 
	 * @return void
	 */
	public function testOperatorSmallerThanIsDetectedAndHandled()
	{
		$mock = Mockery::mock('Query');
		
		$mock
			->shouldReceive('where')
			->with('id', '<', 3)
			->once()
			->andReturn(null);

		(new ResourceFilteringScopeStub())->scopeFilter($mock, ['id' => '<3']);
	}

	/**
	 * Test if the like operator is detected and handled
	 * 
	 * @return void
	 */
	public function testOperatorLikeIsDetectedAndHandled()
	{
		$mock = Mockery::mock('Query');
		
		$mock
			->shouldReceive('where')
			->with('name', 'LIKE', '%abc%')
			->once()
			->andReturn(null);

		(new ResourceFilteringScopeStub())->scopeFilter($mock, ['name' => '|abc']);
	}

	/**
	 * Test if a simple relation can be filtered.
	 * 
	 * @return void
	 */
	public function testResourceRelationCanBeFiltered()
	{
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
		$mock = Mockery::mock('Query');
		
		$mock
			->shouldReceive('where')
			->with('id', '=', 3)
			->once()
			->andReturn(null);

		(new ResourceFilteringScopeStub())->scopeFilter(
			new ResourceQueryStub($mock),
			['fake_relation-id' => '=3']
		);
	}

	/**
	 * Test if the bigger than operator is detected and handled on relation
	 * 
	 * @return void
	 */
	public function testOperatorBiggerThanIsDetectedAndHandledOnRelation()
	{
		$mock = Mockery::mock('Query');
		
		$mock
			->shouldReceive('where')
			->with('id', '>', 3)
			->once()
			->andReturn(null);

		(new ResourceFilteringScopeStub())->scopeFilter(
			new ResourceQueryStub($mock),
			['fake_relation-id' => '>3']
		);
	}

	/**
	 * Test if the smaller than operator is detected and handled on relation
	 * 
	 * @return void
	 */
	public function testOperatorSmallerThanIsDetectedAndHandledOnRelation()
	{
		$mock = Mockery::mock('Query');
		
		$mock
			->shouldReceive('where')
			->with('id', '<', 3)
			->once()
			->andReturn(null);

		(new ResourceFilteringScopeStub())->scopeFilter(
			new ResourceQueryStub($mock),
			['fake_relation-id' => '<3']
		);
	}

	/**
	 * Test if the like operator is detected and handled on relation
	 * 
	 * @return void
	 */
	public function testOperatorLikeIsDetectedAndHandledOnRelation()
	{
		$mock = Mockery::mock('Query');
		
		$mock
			->shouldReceive('where')
			->with('name', 'LIKE', '%abc%')
			->once()
			->andReturn(null);

		(new ResourceFilteringScopeStub())->scopeFilter(
			new ResourceQueryStub($mock),
			['fake_relation-name' => '|abc']
		);
	}

}