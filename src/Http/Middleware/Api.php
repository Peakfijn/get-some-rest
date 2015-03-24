<?php namespace Peakfijn\GetSomeRest\Http\Middleware;

use Closure;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Contracts\Routing\Middleware;
use Illuminate\Http\Response;
use League\OAuth2\Server\Exception\OAuthException;
use Peakfijn\GetSomeRest\Auth\Guard;
use Peakfijn\GetSomeRest\Contracts\RestException;
use Peakfijn\GetSomeRest\Encoders\EncoderFactory;
use Peakfijn\GetSomeRest\Mutators\MutatorFactory;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
     * @param \Peakfijn\GetSomeRest\Mutators\MutatorFactory $mutatorFactory
     * @param \Peakfijn\GetSomeRest\Auth\Guard              $guard
     */
    public function __construct(
        EncoderFactory $encoderFactory,
        MutatorFactory $mutatorFactory,
        Guard $guard
    ) {
        $this->encoderFactory = $encoderFactory;
        $this->mutatorFactory = $mutatorFactory;
        $this->guard = $guard;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     * @throws \Exception
     * @throws \Peakfijn\GetSomeRest\Contracts\RestException
     */
    public function handle($request, Closure $next)
    {
        $mutator = $this->mutatorFactory->make($request);
        $encoder = $this->encoderFactory->make($request);

        try {
            $this->guard->resource->isValidRequest(false);
            $response = $next($request);
        } catch (RestException $error) {
            if (!$error->shouldBeCaught()) {
                throw $error;
            }

            $response = $error->getResponse();
        } catch (OAuthException $error) {
            $response = response($error->getMessage(), $error->httpStatusCode);
        } catch (HttpException $error) {
            $response = response($error->getMessage(), $error->getStatusCode());
        } catch (ModelNotFoundException $error) {
            $response = response('Could not find the requested "'. $error->getModel() .'".', 404);
        }

        if (! $response instanceof Response) {
            $response = response($response);
        }

        $response = $mutator->mutate($request, $response);
        $response = $encoder->encode($request, $response);

        $response->header('Content-Type', $encoder->getContentType());

        return $response;
    }
}
