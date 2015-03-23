<?php namespace Peakfijn\GetSomeRest\Mutators;

use RuntimeException;
use Illuminate\Config\Repository as Config;
use Illuminate\Http\Request;
use Peakfijn\GetSomeRest\Contracts\Factory;

class MutatorFactory implements Factory
{
    /**
     * The default mutator to use.
     *
     * @var string
     */
    protected $default;

    /**
     * All registered mutators.
     *
     * @var array
     */
    protected $mutators;

    /**
     * Create the factory, and let it hook up his configuration.
     *
     * @param \Illuminate\Config\Repository $config
     */
    public function __construct(Config $config)
    {
        $this->mutators = $config->get('get-some-rest.mutators');
        $this->default = $config->get('get-some-rest.default-mutator');
    }

    /**
     * Spawn a new mutator instance.
     *
     * @throws \RuntimeException
     * @param  \Illuminate\Http\Request $request
     * @return \Peakfijn\GetSomeRest\Contracts\Mutator
     */
    public function make(Request $request)
    {
        $mutator = $this->getMutatorFromRequest($request);

        if (!empty($mutator)) {
            return new $this->mutators[$mutator];
        }

        if (array_key_exists($this->default, $this->mutators)) {
            return new $this->mutators[$this->default];
        }

        throw new RuntimeException('No usable mutator found.');
    }

    /**
     * Get the requested mutation, from the request.
     * For example:
     *   - application/json                  =>
     *   - application/vnd.api+json          =>
     *   - application/vnd.api.v1+xml        =>
     *   - application/vnd.api.v4.plain+json => plain
     *   - application/vnd.api.v7.plain      => plain
     *   - application/vnd.api.v9.meta+yml   => meta
     *
     * @param  \Illuminate\Http\Request $request
     * @return string|null
     */
    protected function getMutatorFromRequest(Request $request)
    {
        $requested = $request->header('accept');
        $pattern = '/application\/vnd\.[a-z0-9-]+\.[a-z0-9-]+\.([a-z0-9-_]+)/';

        foreach (explode(',', $requested) as $type) {
            if (preg_match($pattern, trim($type), $matches) !== false) {
                if (!empty($matches)) {
                    $mutator = strtolower(end($matches));

                    if (array_key_exists($mutator, $this->mutators)) {
                        return $mutator;
                    }
                }
            }
        }
    }
}
