<?php namespace Peakfijn\GetSomeRest\Usables;

use Bycedric\Inquiry\Facades\Inquiry;

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
		$attributes = array_keys($this->getArrayableAttributes());

		// itterate over the provided queries
		foreach( $values as $key => $value )
		{
			// set some main variables
			$inquiry  = Inquiry::get($key);
			$operator = null;
			$relation = null;
			$method   = null;

			// check if a valid operator was supplied
			if( !$inquiry->hasOperator() )
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

			// get the operator query
			$operator = $inquiry->getOperator();

			// if a relation was provided
			if( $relation !== null )
			{
				// apply relation query
				$query->whereHas($method, function( $query ) use ( $relation, $operator )
				{
					// set some variables
					$value = $operator->getValue();

					// check if it's a like query
					if( $operator->getMethod() == 'LIKE' )
					{
						$value = '%'. $value .'%';
					}

					// apply final query
					$query->where($relation->getValue(), $operator->getMethod(), $value);
				});

				// query already applied
				continue;
			}

			// apply query
			$query->where($inquiry->getKey(), $operator->getMethod(), $operator->getValue());
		}

		// return the query for method chaining
		return $query;
	}

}
