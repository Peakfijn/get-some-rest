<?php namespace Peakfijn\GetSomeRest\Contracts;

interface Factory
{
    /**
     * Get a new instance from the factory, by name.
     * If nothing was found, it returns the default instance.
     *
     * @param  string $name
     * @return object|null
     */
    public function make($name);

    /**
     * Check if the factory has a registered instance for the provided name.
     *
     * @param  string $name
     * @return boolean
     */
    public function contains($name);

    /**
     * Register an instance to the factory.
     *
     * @param  string $name
     * @param  mixed  $encoder
     * @return void
     */
    public function register($name, $value);

    /**
     * Set a registered instance as default.
     *
     * @param  string $name
     * @return object|null
     */
    public function defaults($name);
}
