<?php namespace Peakfijn\GetSomeRest\Http\Middleware;

use Closure;
use Illuminate\Contracts\Routing\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Peakfijn\GetSomeRest\Contracts\RestException as RestExceptionContract;
use Peakfijn\GetSomeRest\Contracts\EncoderFactory as EncoderFactoryContract;
use Peakfijn\GetSomeRest\Contracts\MutatorFactory as MutatorFactoryContract;
use Peakfijn\GetSomeRest\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface as HttpExceptionContract;

class Api implements Middleware
{
    /**
     * The encoder factory.
     *
     * @var \Peakfijn\GetSomeRest\Contracts\EncoderFactory
     */
    protected $encoders;

    /**
     * The mutator factory.
     *
     * @var \Peakfijn\GetSomeRest\Contracts\MutatorFactory
     */
    protected $mutators;

    /**
     * Create a new API middleware instance.
     * It uses both encoder as mutator factories to determine the requested instance.
     *
     * @param \Peakfijn\GetSomeRest\Contracts\EncoderFactory $encoders
     * @param \Peakfijn\GetSomeRest\Contracts\MutatorFactory $mutators
     */
    public function __construct(
        EncoderFactoryContract $encoders,
        MutatorFactoryContract $mutators
    ) {
        $this->encoders = $encoders;
        $this->mutators = $mutators;
    }

    /**
     * Handle an incoming request.
     *
     * @throws \Exception
     * @throws \Peakfijn\GetSomeRest\Contracts\RestException
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $encoder = $this->encoders->makeFromRequest($request);
        $mutator = $this->mutators->makeFromRequest($request);

        try {
            $response = $next($request);
        } catch (RestExceptionContract $error) {
            if (!$error->shouldBeCaught()) {
                throw $error;
            }

            $response = $error->getResponse();
        } catch (HttpExceptionContract $error) {
            $response = new Response(
                $error->getMessage(),
                $error->getStatusCode()
            );
        } catch (ModelNotFoundException $error) {
            $response = new Response(
                'Could not find "' . $error->getModel() . '" with the requested id.',
                404
            );
        }

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
