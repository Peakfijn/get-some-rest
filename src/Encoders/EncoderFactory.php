<?php namespace Peakfijn\GetSomeRest\Encoders;

use RuntimeException;
use Illuminate\Config\Repository as Config;
use Illuminate\Http\Request;
use Peakfijn\GetSomeRest\Contracts\Factory;

class EncoderFactory implements Factory
{
    /**
     * The default encoder to use.
     *
     * @var string
     */
    protected $default;

    /**
     * All registered encoders, by mime type.
     *
     * @var array
     */
    protected $encoders;

    /**
     * Create the factory, and let it look up his configuration.
     *
     * @param  \Illuminate\Config\Repository $config
     */
    public function __construct(Config $config)
    {
        $this->encoders = $config->get('get-some-rest.encoders');
        $this->default = $config->get('get-some-rest.default-encoder');
    }

    /**
     * Spawn a new encoder instance.
     *
     * @throws \RuntimeException
     * @param  \Illuminate\Http\Request $request
     * @return \Peakfijn\GetSomeRest\Contracts\Encoder
     */
    public function make(Request $request)
    {
        $encoder = $this->getEncoderFromRequest($request);

        if (!empty($encoder)) {
            return new $this->encoders[$encoder];
        }

        if (array_key_exists($this->default, $this->encoders)) {
            return new $this->encoders[$this->default];
        }

        throw new RuntimeException('No usable encoder found.');
    }

    /**
     * Get the requested encoder, from the request.
     * For example:
     *   - application/json            => json
     *   - application/xml             => xml
     *   - application/vnd.api+json    => json
     *   - application/vnd.api.v1+json => json
     *   - application/vnd.api+yaml    => yaml
     *
     * @param  \Illuminate\Http\Request $request
     * @return string|null
     */
    protected function getEncoderFromRequest(Request $request)
    {
        $requested = $request->header('accept');
        $pattern = '/application\/(vnd\.[a-z0-9.-]+\+)?([a-z]+)/';

        foreach (explode(',', $requested) as $type) {
            if (preg_match($pattern, trim($type), $matches) !== false) {
                if (!empty($matches)) {
                    $encoder = strtolower(end($matches));

                    if (array_key_exists($encoder, $this->encoders)) {
                        return $encoder;
                    }
                }
            }
        }
    }
}
