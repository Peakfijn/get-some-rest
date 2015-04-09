<?php namespace Peakfijn\GetSomeRest\Http\Middleware;

use Closure;
use Illuminate\Contracts\Routing\Middleware;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\Relation as EloquentRelation;
use Illuminate\Http\Request;
use Peakfijn\GetSomeRest\Contracts\Anatomy as AnatomyContract;
use Peakfijn\GetSomeRest\Contracts\Dissector as DissectorContract;
use Peakfijn\GetSomeRest\Contracts\RestException as RestExceptionContract;
use Peakfijn\GetSomeRest\Contracts\EncoderFactory as EncoderFactoryContract;
use Peakfijn\GetSomeRest\Contracts\MutatorFactory as MutatorFactoryContract;
use Peakfijn\GetSomeRest\Contracts\MethodFactory as MethodFactoryContract;
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

    protected $methods;
    protected $dissector;

    protected $anatomy;

    /**
     * Create a new API middleware instance.
     * It uses both encoder as mutator factories to determine the requested instance.
     *
     * @param \Peakfijn\GetSomeRest\Contracts\EncoderFactory $encoders
     * @param \Peakfijn\GetSomeRest\Contracts\MutatorFactory $mutators
     */
    public function __construct(
        EncoderFactoryContract $encoders,
        MutatorFactoryContract $mutators,
        MethodFactoryContract $methods,
        DissectorContract $dissector,
        AnatomyContract $anatomy
    ) {
        $this->encoders = $encoders;
        $this->mutators = $mutators;

        $this->methods = $methods;
        $this->dissector = $dissector;

        $this->anatomy = $anatomy;
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

    /**
     * Retrieve the methods from the query, and apply to the provided query.
     * When it's finished, execute the ->get() or ->first() to return the final data only.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder $query
     * @return mixed
     */
    protected function executeMethods(Request $request, $query)
    {
        $methods = $this->dissector->methods($request);

        foreach ($methods as $key => $value) {
            $method = $this->methods->make($key);

            if (!empty($method)) {
                $method = new $method;
                $query = $method->execute($value, $query);
            }
        }

        return $query;
    }

    /**
     * Execute the provided filters.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  mixed $query
     * @return mixed
     */
    protected function executeFilters(Request $request, $query)
    {
        $filters = $this->dissector->filters($request);

        foreach ($filters as $key => $values) {
            $query = $query->whereIn($key, $values);
        }

        return $query;
    }

    /**
     * Finalize the query, returning the response.
     *
     * @param  mixed $query
     * @return mixed
     */
    protected function finalizeQuery($query)
    {
        if (!$this->anatomy->shouldBeCollection()) {
            $id = $this->anatomy->getResourceId();

            if ($this->anatomy->hasRelationId()) {
                $id = $this->anatomy->getRelationId();
            }

            return $query->firstOrFail($id);
        }

        return $query->get();
    }
}
