<?php namespace Peakfijn\GetSomeRest\Usables;

trait ResourceFilteringScopeTrait {

	/**
	 * All allowed operators with their SQL method equivalent.
	 * 
	 * @var array
	 */
	private $operators = [
		'=' => '=', 
		'>' => '>',
		'<' => '<',
		'|' => 'LIKE',
	];

	/**
	 * Restrict the resource to the given filters.
	 * These are the main functionalities:
	 * 
	 *   - Scope on the (single) relation!
	 *     Use "-" as delimeter between the resource name and attribute.
	 *     > /v1/items?article-drug_amount=1000mg
	 *
	 *   - Use different operators!
	 *     Use one of the operators as first character in the value.
	 *     > /v1/items?article-cost=>5
	 *     > /v1/items?article-cost=<20
	 *     > /v1/items?article-name=|panadol
	 * 
	 * @param  \Illuminate\Database\Eloquent\Builder $query
	 * @param  array  $values  (default: array())
	 * @param  string $relation_delimeter  (default: -)
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeFilter( $query, array $values = array(), $relation_delimeter = '-' )
	{
		$attributes = $this->getFilterableAttributes();

		foreach( $values as $attribute => $value )
		{
			$relation = null;
			$relation_attribute = null;
			
			if( strpos($attribute, $relation_delimeter) !== false )
			{
				list($relation, $relation_attribute) = explode($relation_delimeter, $attribute);
				$relation = camel_case($relation);
			}

			if( !in_array($attribute, $attributes) )
			{
				if( !method_exists($this, $relation) && !in_array($relation, $attributes) )
				{
					continue;
				}
			}

			if( !is_null($relation) )
			{
				$query->whereHas(camel_case($relation), function( $query ) use ( $relation_attribute, $value )
				{
					$this->applyFilterableToQuery($query, $relation_attribute, $value);
				});
			}
			else
			{
				$this->applyFilterableToQuery($query, $attribute, $value);
			}
		}
	}

	/**
	 * Apply the given values to the query.
	 * 
	 * @param  \Illuminate\Database\Eloquent\Builder $query
	 * @param  string $attribute
	 * @param  string $value
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	protected function applyFilterableToQuery( $query, $attribute, $value )
	{
		$operators = $this->getFilterableOperators();
		$operator  = '=';
		
		if( in_array($value[0], array_keys($operators)) )
		{
			$operator = $value[0];
			$value    = substr($value, 1);
		}

		$method = $operators[$operator];
		
		if( $method == 'LIKE' )
		{
			$value = '%'. str_replace(' ', '%', $value) .'%';
		}

		return $query->where($attribute, $method, $value);
	}

	/**
	 * Get all attributes that are allowed for filtering.
	 * 
	 * @return array
	 */
	protected function getFilterableAttributes()
	{
		return array_keys($this->getArrayableAttributes());
	}

	/**
	 * Get all allowed operators with their query function.
	 * 
	 * @return array
	 */
	protected function getFilterableOperators()
	{
		return $this->operators;
	}

}