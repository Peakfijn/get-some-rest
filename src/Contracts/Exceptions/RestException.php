<?php namespace Peakfijn\GetSomeRest\Contracts\Exceptions;

interface RestException
{
    /**
     * When throwing exceptions in the API, most of those exceptions should be
     * caught. In some cases you might want to let the exception bubble up to
     * the surface.
     *
     * @return boolean
     */
    public function shouldBeCaught();

    /**
     * Exceptions in API's can be a real helper.
     * When executing a task, you probably have some checks before the task is
     * executed. If one of those checks fail, you can throw an exception and all
     * future tasks are stopped. The only thing left to do is respond to the
     * user why the task did not occur. This specific function turns an
     * exception into a readable API response.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getResponse();
}
