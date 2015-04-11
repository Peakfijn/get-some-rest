<?php namespace Peakfijn\GetSomeRest\Http\Middleware;

use Closure;
use Illuminate\Contracts\Routing\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Peakfijn\GetSomeRest\Contracts\Encoders\Encoder as EncoderContract;
use Peakfijn\GetSomeRest\Contracts\Mutators\Mutator as MutatorContract;
use Peakfijn\GetSomeRest\Contracts\Exceptions\RestException as RestExceptionContract;
use Peakfijn\GetSomeRest\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface as HttpExceptionContract;

class Api implements Middleware
{
    /**
     * The encoder to use for the response.
     *
     * @var \Peakfijn\GetSomeRest\Contracts\Encoders\Encoder
     */
    protected $encoder;

    /**
     * The mutator to use for the response.
     *
     * @var \Peakfijn\GetSomeRest\Contracts\Mutators\Mutator
     */
    protected $mutator;

    /**
     * Create a new API middleware instance.
     * It uses both encoder as mutator factories to determine the requested instance.
     *
     * @param \Peakfijn\GetSomeRest\Contracts\Encoders\Encoder $encoder
     * @param \Peakfijn\GetSomeRest\Contracts\Mutators\Mutator $mutator
     *
     */
    public function __construct(
        EncoderContract $encoder,
        MutatorContract $mutator
    ) {
        $this->encoder = $encoder;
        $this->mutator = $mutator;
    }

    /**
     * Handle an incoming request.
     *
     * @throws \Exception
     * @throws \Peakfijn\GetSomeRest\Contracts\Exceptions\RestException
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
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
            ->setEncoder($this->encoder)
            ->setMutator($this->mutator);
    }
}
