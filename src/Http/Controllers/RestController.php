<?php namespace Peakfijn\GetSomeRest\Http\Controllers;

use Illuminate\Routing\Controller;

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
