<?php namespace Peakfijn\GetSomeRest\Http\Middleware;

use Closure;
use Illuminate\Contracts\Routing\Middleware;
use Illuminate\Http\Request;
use Peakfijn\GetSomeRest\Factories\EncoderFactory;
use Peakfijn\GetSomeRest\Factories\MutatorFactory;
use Peakfijn\GetSomeRest\Http\Response as Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class Api implements Middleware
{
    /**
     * The encoder factory.
     *
     * @var \Peakfijn\GetSomeRest\Contracts\Factory
     */
    protected $encoders;

    /**
     * The mutator factory.
     *
     * @var \Peakfijn\GetSomeRest\Contracts\Factory
     */
    protected $mutators;

    /**
     * Create a new API middleware instance.
     * It uses both encoder as mutator factories to determine the requested instance.
     *
     * @param \Peakfijn\GetSomeRest\Factories\EncoderFactory $encoders
     * @param \Peakfijn\GetSomeRest\Factories\MutatorFactory $mutators
     */
    public function __construct(
        EncoderFactory $encoders,
        MutatorFactory $mutators
    ) {
        $this->encoders = $encoders;
        $this->mutators = $mutators;
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
        $encoder = $this->encoders->makeFromRequest($request);
        $mutator = $this->mutators->makeFromRequest($request);

        $response = $next($request);

        if ($response instanceof SymfonyResponse) {
            $response = Response::makeFromExisting($response);
        } else {
            $response = new Response($response);
        }

        return $response
            ->setEncoder($encoder)
            ->setMutator($mutator);
    }
}
