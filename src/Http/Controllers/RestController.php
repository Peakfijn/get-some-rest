<?php namespace Peakfijn\GetSomeRest\Http\Controllers;

use Illuminate\Routing\Controller;
use Peakfijn\GetSomeRest\Http\Controllers\ResourceDestroyTrait;
use Peakfijn\GetSomeRest\Http\Controllers\ResourceIndexTrait;
use Peakfijn\GetSomeRest\Http\Controllers\ResourceShowTrait;
use Peakfijn\GetSomeRest\Http\Controllers\ResourceStoreTrait;
use Peakfijn\GetSomeRest\Http\Controllers\ResourceUpdateTrait;

class RestController extends Controller
{
    use ResourceIndexTrait,
        ResourceShowTrait,
        ResourceStoreTrait,
        ResourceUpdateTrait,
        ResourceDestroyTrait;
}
