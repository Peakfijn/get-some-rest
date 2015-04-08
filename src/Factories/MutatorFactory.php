<?php namespace Peakfijn\GetSomeRest\Factories;

use RuntimeException;
use Illuminate\Http\Request;
use Peakfijn\GetSomeRest\Contracts\Mutator as MutatorContract;
use Peakfijn\GetSomeRest\Contracts\MutatorFactory as MutatorFactoryContract;

class MutatorFactory extends Factory implements MutatorFactoryContract
{
    /**
     * Register an instance to the factory.
     *
     * @throws \RuntimeException
     * @param  string $name
     * @param  mixed $value
     * @return object|null
     */
    public function register($name, $value)
    {
        if (!$value instanceof MutatorContract) {
            throw new RuntimeException('The instance tried to register is not a Mutator.');
        }

        return parent::register($name, $value);
    }

    /**
     * Get a new mutator from the factory, by request.
     * It tries and fetch a mutator, by the accept header.
     * If nothing was found, it returns the default mutator.
     *
     * The mutator is extracted using the following syntax:
     *   - application/json                  =>
     *   - application/vnd.api+json          =>
     *   - application/vnd.api.v1+xml        =>
     *   - application/vnd.api.v4.plain+json => plain
     *   - application/vnd.api.v7.plain      => plain
     *   - application/vnd.api.v9.meta+yml   => meta
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Peakfijn\GetSomeRest\Contracts\Mutator|null
     */
    public function makeFromRequest(Request $request)
    {
        $types = explode(',', $request->header('accept'));
        $pattern = '/application\/vnd\.[a-z0-9-]+\.[a-z0-9-]+\.([a-z0-9-_]+)/';

        foreach ($types as $type) {
            if (preg_match($pattern, trim($type), $matches) !== false) {
                $mutator = strtolower(end($matches));

                if ($this->contains($mutator)) {
                    return $this->instances[$mutator];
                }
            }
        }

        return $this->defaults;
    }
}
