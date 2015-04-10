<?php namespace Peakfijn\GetSomeRest\Factories;

use RuntimeException;
use Peakfijn\GetSomeRest\Contracts\Factories\MethodFactory as MethodFactoryContract;

class MethodFactory extends Factory implements MethodFactoryContract
{
    /**
     * The method prefix.
     *
     * @var string
     */
    protected $prefix;

    /**
     * Set a registered instance as default.
     *
     * @param  string $name
     * @return object|null
     */
    public function defaults($name)
    {
        throw new RuntimeException(
            'The methods factory is not using defaults'
        );
    }

    /**
     * Get the method prefix.
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Set the method prefix for retrieving methods from the request.
     *
     * @param string $prefix
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }
}
