<?php namespace Peakfijn\GetSomeRest\Http\Url;

use Peakfijn\GetSomeRest\Http\Url\Resolvers\ResourceResolver;

class Url
{
    /**
     * The resource class holder.
     *
     * @var string|null
     */
    protected $resourceClass;

    /**
     * The resource identifier.
     *
     * @var string|int|null
     */
    protected $resourceId;

    /**
     * Create a new URL instance.
     *
     * @param array                                                      $segments
     * @param \Peakfijn\GetSomeRest\Http\Urls\Resolvers\ResourceResolver $resolver
     */
    public function __construct(array $segments, ResourceResolver $resolver)
    {
        $this->parse($segments, $resolver);
    }

    /**
     * Parse the lose url segments, extracting and storing all information.
     *
     * @param  array                                                      $segments
     * @param  \Peakfijn\GetSomeRest\Http\Urls\Resolvers\ResourceResolver $resolver
     * @return void
     */
    protected function parse(array $segments, ResourceResolver $resolver)
    {
        foreach ($segments as $key => $value) {
            $resource = $resolver->resolve($value);

            if ($resource) {
                $this->resourceClass = $resource;
                $this->parseExtra(array_slice($segments, $key + 1));
                break;
            }
        }
    }

    /**
     * Parse extra information.
     * This should receive all url segments, AFTER the resource.
     *
     * @param  array  $segments
     * @return void
     */
    private function parseExtra(array $segments)
    {
        if (count($segments) > 0) {
            $this->resourceId = $segments[0];
        }
    }

    /**
     * Get the main resource class, with namespace.
     *
     * @return string|null
     */
    public function resourceClass()
    {
        return $this->resourceClass;
    }

    /**
     * Get the main resource identifier.
     *
     * @return string|int|null
     */
    public function resourceId()
    {
        return $this->resourceId;
    }

}
