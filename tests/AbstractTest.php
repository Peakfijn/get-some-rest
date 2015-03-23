<?php namespace Peakfijn\GetSomeRest\Tests;

use Mockery;
use PHPUnit_Framework_TestCase;
use ReflectionMethod;
use ReflectionProperty;

/**
 * This class acts as a foundation for all tests.
 * Here extra helper functions are included to help test everything.
 *
 * @author Cedric van Putten <me@bycedric.com>
 */
abstract class AbstractTest extends PHPUnit_Framework_TestCase
{
    /**
     * When a test finishes, clear all existing mocks.
     *
     * @return null
     */
    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * Call and return an internal, private or protected, method from an object.
     *
     * @param  object $object
     * @param  string $method
     * @param  array  $args (default: [])
     * @return mixed
     */
    public function callInternalMethod($object, $method, array $args = array())
    {
        $reflection = new ReflectionMethod(get_class($object), $method);
        $reflection->setAccessible(true);

        if (!empty($args)) {
            return $reflection->invokeArgs($object, $args);
        }

        return $reflection->invoke($object);
    }

    /**
     * Get an internal, private or protected, property from an object.
     *
     * @param  object $object
     * @param  string $property
     * @return mixed
     */
    public function getInternalProperty($object, $property)
    {
        $reflection = new ReflectionProperty(get_class($object), $property);
        $reflection->setAccessible(true);

        return $reflection->getValue($object);
    }

    /**
     * Set the value of an internal, private or protected, property from an object.
     *
     * @param  object $object
     * @param  string $property
     * @param  mixed  $value
     * @return void
     */
    public function setInternalProperty($object, $property, $value)
    {
        $reflection = new ReflectionProperty(get_class($object), $property);
        $reflection->setAccessible(true);
        $reflection->setValue($object, $value);
    }
}
