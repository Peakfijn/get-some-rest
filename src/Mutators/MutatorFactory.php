<?php namespace Peakfijn\GetSomeRest\Mutators;

use Illuminate\Http\Request;

class MutatorFactory {

    function __construct()
    {
        $this->mutators = config('get-some-rest.mutators');
        $this->defaultMutator = (config('get-some-rest.defaultMutator')) ?: array_keys($this->mutators)[0];
    }

    /**
     * Make a new mutator.
     *
     * @param Request $request
     * @return mixed
     * @throws UndefinedMutatorException
     */
    public function make(Request $request)
    {
        $mutatorType = ($request->get('mutator')) ?: $this->defaultMutator;
        if ( ! array_key_exists($mutatorType, $this->mutators)) {
            throw new UndefinedMutatorException;
        }
        return new $this->mutators[$mutatorType];
    }

}