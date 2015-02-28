<?php namespace Peakfijn\GetSomeRest\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Contracts\Routing\Middleware;
use Peakfijn\GetSomeRest\Encoders\EncoderFactory;
use Peakfijn\GetSomeRest\Http\Exceptions\ExceptionFactory;
use Peakfijn\GetSomeRest\Http\Response;
use Peakfijn\GetSomeRest\Mutators\MutatorFactory;

class Api implements Middleware {

    protected $exceptionFactory;
    protected $encoderFactory;
    protected $mutatorFactory;

    /**
     * @param ExceptionFactory $exceptionFactory
     * @param EncoderFactory   $encoderFactory
     * @param MutatorFactory   $mutatorFactory
     */
    public function __construct(
        ExceptionFactory $exceptionFactory,
        EncoderFactory $encoderFactory,
        MutatorFactory $mutatorFactory
    ) {
        $this->exceptionFactory = $exceptionFactory;
        $this->encoderFactory = $encoderFactory;
        $this->mutatorFactory = $mutatorFactory;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $response = Response::makeFromResponse($next($request));
        } catch (\Exception $exception) {
            $exception = $this->exceptionFactory->make($exception);
            $response = $exception->getResponse();
        }

        $response->setMutator($this->mutatorFactory->make());
        $response->setEncoder($this->encoderFactory->make($request));

        return $response->mutate()->encode();
    }
}
