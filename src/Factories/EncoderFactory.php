<?php namespace Peakfijn\GetSomeRest\Factories;

use RuntimeException;
use Illuminate\Http\Request;
use Peakfijn\GetSomeRest\Contracts\Encoder as EncoderContract;

class EncoderFactory extends Factory
{
    /**
     * Register an instance to the factory.
     *
     * @throws \RuntimeException if the value is not an Encoder
     * @param  string $name
     * @param  mixed  $encoder
     * @return void
     */
    public function register($name, $value)
    {
        if ($value instanceof EncoderContract) {
            return parent::register($name, $value);
        }

        throw new RuntimeException('The instance tried to register is not an Encoder.');
    }

    /**
     * Get a new mutator from the factory, by request.
     * It tries and fetch a mutator, by the accept header.
     * If nothing was found, it returns the default mutator.
     *
     * The encoder is extracted using the following syntax:
     *   - application/json            => json
     *   - application/xml             => xml
     *   - application/vnd.api+json    => json
     *   - application/vnd.api.v1+json => json
     *   - application/vnd.api+yaml    => yaml
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Peakfijn\GetSomeRest\Contracts\Mutator|null
     */
    public function makeFromRequest(Request $request)
    {
        $types = explode(',', $request->header('accept'));
        $pattern = '/application\/(vnd\.[a-z0-9.-]+\+)?([a-z]+)/';

        foreach ($types as $type) {
            if (preg_match($pattern, trim($type), $matches) !== false) {
                $encoder = strtolower(end($matches));

                if ($this->contains($encoder)) {
                    return $this->instances[$encoder];
                }
            }
        }

        return $this->defaults;
    }
}
