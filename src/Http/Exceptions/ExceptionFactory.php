<?php namespace Peakfijn\GetSomeRest\Http\Exceptions;

use Exception;
use Illuminate\Console\AppNamespaceDetectorTrait;

class ExceptionFactory {

    use AppNamespaceDetectorTrait;

    /**
     * Make a new exception
     *
     * @param Exception $thrownException
     * @return RestException
     * @throws
     * @throws RestException
     */
    public function make(Exception $thrownException)
    {
        if ($this->hasOverride($thrownException)) {
            $exception = $this->resolveOverride($thrownException);
        } else {
            $exception = RestException::makeFromException($thrownException);
        }

        if ( ! $exception->shouldBeCaught()) {
            throw $exception;
        }

        return $exception;
    }

    /**
     * Get the override path for an exception.
     *
     * @param Exception $exception
     * @return string
     */
    public function getOverride(Exception $exception)
    {
        $namespace = $this->getAppNamespace();
        $exceptionName = (new \ReflectionClass($exception))->getShortName();

        return $namespace . 'Exceptions\\GetSomeRest\\' . $exceptionName;
    }

    /**
     * Check if an exception has an override.
     *
     * @param Exception $exception
     * @return bool|string
     */
    protected function hasOverride(Exception $exception)
    {
        $override = $this->getOverride($exception);
        if ( ! class_exists($override)) {
            return false;
        }

        return $override;
    }

    /**
     * Resolve the override exception.
     *
     * @param Exception $exception
     */
    protected function resolveOverride(Exception $exception)
    {
        $override = $this->hasOverride($exception);

        return new $override($exception);
    }
}