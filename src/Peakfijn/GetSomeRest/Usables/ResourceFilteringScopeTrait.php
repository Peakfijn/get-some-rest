<?php namespace Peakfijn\GetSomeRest\Usables;

use Bycedric\Inquiry\Facades\Inquiry;
use Bycedric\Inquiry\Queries\RangeQuery;

trait ResourceFilteringScopeTrait {

	/**
	 * Restrict the resource to the given filters.
	 * These are the main functionalities:
	 * 
	 *   - Scope on the (single) relation!
	 *     Use "-" as delimeter between the resource name and attribute.
	 *     > /v1/items?article:drug_amount=1000mg
	 *
	 *   - Use different operators!
	 *     Use one of the operators as first character in the value.
	 *     > /v1/items?article:cost=]5
	 *     > /v1/items?article:cost=[20
	 *     > /v1/items?article:name=~panadol
	 *     
	 *   - It now uses byCedric/Inquiry package!
	 * 
	 * @param  \Illuminate\Database\Eloquent\Builder $query
	 * @param  array  $values  (default: array())
	 * @param  string $relation_delimeter  (default: -)
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeFilter( $query, array $values = array() )
	{
		// all allowed attributes
		$attributes = array_keys($this->getArrayableItems(array_merge($this->attributes, array_flip($this->visible))));

		// itterate over the provided queries
		foreach( $values as $key => $value )
		{
			// set some main variables
			$inquiry  = Inquiry::get($key);
			$operator = null;
			$relation = null;
			$method   = null;

			// check if a valid operator was supplied
			if( !$inquiry->hasOperator() && !$inquiry->hasRange() )
			{
				continue;
			}

			// check if the key is a filterable attribute
			if( !in_array($inquiry->getKey(), $attributes) )
			{
				// if it doesn't has a relation, it's definitly not the good attribute
				if( !$inquiry->swap()->hasRelation() )
				{
					continue;
				}

				// get the relation, with the key (func:attr) as main value
				$relation = $inquiry->swap()->getRelation();
				$method   = camel_case($relation->getRelated());

				// check if the relation is allowed
				if( !method_exists($this, $method) || !in_array($method, $attributes) )
				{
					continue;
				}
			}

			// check if the operator is a range query
			if( $inquiry->hasRange() )
			{
				// get the range values
				$operator = $inquiry->getRange();
			}
			else
			{
				// get the operator query
				$operator = $inquiry->getOperator();
			}

			// if a relation was provided
			if( $relation !== null )
			{
				// apply relation query
				$query->whereHas($method, function( $query ) use ( $operator, $relation )
				{
					// apply final query
					$this->applyQuery($query, $relation->getValue(), $operator);
				});

				// query already applied
				continue;
			}

			// apply query
			$this->applyQuery($query, $inquiry->getKey(), $operator);
		}

		// return the query for method chaining
		return $query;
	}

	/**
	 * Apply the inquiry with an optional operator query to the query itself.
	 * 
	 * @param  \Illuminate\Database\Eloquent\Builder   $query
	 * @param  string                                  $attribute
	 * @param  \Bycedric\Inquiry\Queries\Query $operator
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	private function applyQuery( $query, $attribute, $operator )
	{
		// check if operator is a range query
		if( $operator instanceof RangeQuery )
		{
			// iterate the values and apply to the query for each seperate operator
			foreach( $operator->getValues() as $value )
			{
				$this->applyQuery($query, $attribute, $value);
			}

			// stop executing
			return $query;
		}

		// get the value and method
		$value  = $operator->getValue();
		$method = $operator->getMethod();

		// check if the value is null
		if( is_null($value) )
		{
			// check if the method or operator is an inverter
			if( $operator->isNot() )
			{
				// applying whereNotNull
				return $query->whereNotNull($attribute);
			}
			
			// applying whereNull
			return $query->whereNull($attribute);
		}

		// check if the method is LIKE
		if( $method == 'LIKE' )
		{
			// add % to the value
			$value = '%'. str_replace(' ', '%', $value) .'%';
		}

		// applying query
		return $query->where($attribute, $method, $value);
	}

}
