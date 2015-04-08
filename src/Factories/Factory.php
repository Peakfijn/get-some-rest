<?php namespace Peakfijn\GetSomeRest\Factories;

use Peakfijn\GetSomeRest\Contracts\Factory as FactoryContract;

class Factory implements FactoryContract
{
    /**
     * The registered instance, by name.
     *
     * @var array
     */
    protected $instances = [];

    /**
     * The default instance to use.
     *
     * @var object|null
     */
    protected $defaults;

    /**
     * Get a new instance from the factory, by name.
     * If nothing was found, it returns the default instance.
     *
     * @param  string $name
     * @return object|null
     */
    public function make($name)
    {
        if ($this->contains($name)) {
            return $this->instances[strtolower($name)];
        }

        return $this->defaults;
    }

    /**
     * Check if the factory has a registered instance for the provided name.
     *
     * @param  string $name
     * @return boolean
     */
    public function contains($name)
    {
        return array_key_exists(strtolower($name), $this->instances);
    }

    /**
     * Register an instance to the factory.
     *
     * @param  string $name
     * @param  mixed $value
     * @return object
     */
    public function register($name, $value)
    {
        return $this->instances[strtolower($name)] = $value;
    }

    /**
     * Set a registered instance as default.
     *
     * @param  string $name
     * @return object|null
     */
    public function defaults($name)
    {
        if ($this->contains($name)) {
            return $this->defaults = $this->instances[strtolower($name)];
        }

        return null;
    }
}
