<?php namespace Peakfijn\GetSomeRest\Http;

use Peakfijn\GetSomeRest\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class Pagination {

	/**
	 * The total amount of objects.
	 * 
	 * @var int
	 */
	protected $count  = 0;

	/**
	 * The maximum amount of objects within the response.
	 * 
	 * @var int
	 */
	protected $limit  = 0;

	/**
	 * The amount of objects skipped.
	 * 
	 * @var int
	 */
	protected $offset = 0;

	/**
	 * Create a new Pagination object that describes the "Pagination" info.
	 * Such as count and the current offset.
	 * 
	 * @param int $count
	 * @param int $limit
	 * @param int $offset
	 */
	public function __construct( $count = 0, $limit = 0, $offset = 0 )
	{
		$this->count  = $count;
		$this->limit  = $limit;
		$this->offset = $offset;
	}

	/**
	 * Get the total amount of objects.
	 * 
	 * @return int
	 */
	public function getCount()
	{
		return $this->count;
	}

	/**
	 * Get the maximum amount of objects within the response.
	 * 
	 * @return int
	 */
	public function getLimit()
	{
		return $this->limit;
	}

	/**
	 * Get the amount of objects skipped.
	 * 
	 * @return int
	 */
	public function getOffset()
	{
		return $this->offset;
	}

	/**
	 * Create a response WITH pagination data!
	 * 
	 * @param  mixed $data
	 * @param  mixed $count
	 * @param  mixed $limit
	 * @param  mixed $offset
	 * @return \Peakfijn\GetSomeRest\Http\Response
	 */
	public static function respond( $data, $count = 0, $limit = 0, $offset = 0 )
	{
		return static::respondFromExisting(new Response($data), $count, $limit, $offset);
	}

	/**
	 * Create a response WITH pagination data from an existing response
	 * 
	 * @param  \Symfony\Component\HttpFoundation\Response $response
	 * @param  mixed $count
	 * @param  mixed $limit
	 * @param  mixed $offset
	 * @return \Peakfijn\GetSomeRest\Http\Response
	 */
	public static function respondFromExisting( SymfonyResponse $response, $count = 0, $limit = 0, $offset = 0 )
	{
		if( !$response instanceof Response )
		{
			$response = Response::makeFromExisting($response);
		}

		$pagination = new static($count, $limit, $offset);
		$response->setPagination($pagination);

		return $response;
	}

}