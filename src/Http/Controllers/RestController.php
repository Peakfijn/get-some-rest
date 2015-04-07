<?php namespace Peakfijn\GetSomeRest\Http\Controllers;

use Illuminate\Routing\Controller;
use Peakfijn\GetSomeRest\Http\Controllers\ResourceIndexTrait;
use Peakfijn\GetSomeRest\Http\Controllers\ResourceStoreTrait;
use Peakfijn\GetSomeRest\Http\Controllers\ResourceShowTrait;
use Peakfijn\GetSomeRest\Http\Controllers\ResourceUpdateTrait;
use Peakfijn\GetSomeRest\Http\Controllers\ResourceDestroyTrait;

class RestController extends Controller
{
    use ResourceIndexTrait,
        ResourceStoreTrait,
        ResourceShowTrait,
        ResourceUpdateTrait,
        ResourceDestroyTrait;

    public function __construct()
    {
        $this->middleware('Peakfijn\GetSomeRest\Http\Middleware\Api');
    }
}
