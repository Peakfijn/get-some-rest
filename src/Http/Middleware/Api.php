<?php  namespace Peakfijn\GetSomeRest\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Contracts\Routing\Middleware;
use Peakfijn\GetSomeRest\Http\Exceptions\ExceptionFactory;
use Peakfijn\GetSomeRest\Http\Response;

class Api implements Middleware {

    /**
     * @param ExceptionFactory $exceptionFactory
     */
    public function __construct(ExceptionFactory $exceptionFactory)
    {
        $this->exceptionFactory = $exceptionFactory;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle ($request, Closure $next)
    {
        try {
            $response = Response::makeFromIlluminateResponse($next($request));
        } catch(\Exception $exception) {
            $exception = $this->exceptionFactory->make($exception);
            $response = $exception->getResponse();
        }

        return $response->mutate()->encode();
    }
}
