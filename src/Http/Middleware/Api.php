<?php namespace Peakfijn\GetSomeRest\Http\Middleware;

use Closure;
use Illuminate\Contracts\Routing\Middleware;
use Peakfijn\GetSomeRest\Contracts\RestException;
use Peakfijn\GetSomeRest\Encoders\EncoderFactory;
use Peakfijn\GetSomeRest\Mutators\MutatorFactory;

class Api implements Middleware
{
    /**
     * The encoder factory that spawns the requeste encoder.
     *
     * @var \Peakfijn\GetSomeRest\Encoders\EncoderFactory
     */
    protected $encoderFactory;

    /**
     * The mutator factory that spawns the requested mutator.
     *
     * @var \Peakfijn\GetSomeRest\Mutators\MutatorFactory
     */
    protected $mutatorFactory;

    /**
     * Create a new API Middleware instance.
     *
     * @param \Peakfijn\GetSomeRest\Encoders\EncoderFactory $encoderFactory
     * @param \Peakfijn\GetSomeRest\Encoders\MutatorFactory $mutatorFactory
     */
    public function __construct(
        EncoderFactory $encoderFactory,
        MutatorFactory $mutatorFactory
    ) {
        $this->encoderFactory = $encoderFactory;
        $this->mutatorFactory = $mutatorFactory;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $mutator = $this->mutatorFactory->make($request);
        $encoder = $this->encoderFactory->make($request);

        try {
            $response = $next($request);
        } catch (RestException $error) {
            if (!$error->shouldBeCaught()) {
                throw $error;
            }

            $response = $error->getResponse();
        }

        $response = $mutator->mutate($request, $response);
        $response = $encoder->encode($request, $response);

        $response->header('Content-Type', $encoder->getContentType());

        return $response;
    }
}
