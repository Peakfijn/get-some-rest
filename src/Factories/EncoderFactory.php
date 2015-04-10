<?php namespace Peakfijn\GetSomeRest\Factories;

use RuntimeException;
use Illuminate\Http\Request;
use Peakfijn\GetSomeRest\Contracts\Encoders\Encoder as EncoderContract;
use Peakfijn\GetSomeRest\Contracts\Factories\EncoderFactory as EncoderFactoryContract;

class EncoderFactory extends Factory implements EncoderFactoryContract
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
        if (!$value instanceof EncoderContract) {
            throw new RuntimeException('The instance tried to register is not an Encoder.');
        }

        return parent::register($name, $value);
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
     *   - application/vnd.api+yml     => yml
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Peakfijn\GetSomeRest\Contracts\Encoder|null
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
