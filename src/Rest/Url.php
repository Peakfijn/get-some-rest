<?php namespace Peakfijn\GetSomeRest\Rest;

use Illuminate\Http\Request;
use Peakfijn\GetSomeRest\Contracts\ResourceFactory as ResourceFactoryContract;
use Peakfijn\GetSomeRest\Contracts\Url as UrlContract;

class Url implements UrlContract
{
    /**
     * The resource factory to validate resources with.
     *
     * @var \Peakfijn\GetSomeRest\Contracts\ResourceFactory
     */
    protected $resources;

    /**
     * The requested resource name.
     *
     * @var string
     */
    protected $resourceName;

    /**
     * The requested resource id.
     *
     * @var string|integer|null
     */
    protected $resourceId;

    /**
     * The requested relation name.
     *
     * @var string|null
     */
    protected $relationName;

    /**
     * The requested relation id.
     *
     * @var string|integer|null
     */
    protected $relationId;

    public function __construct(ResourceFactoryContract $resources)
    {
        $this->resources = $resources;
    }

    /**
     * Parse the full request.
     * From the request, all information should be provided.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Peakfijn\GetSomeRest\Contracts\Url
     */
    public function parse(Request $request)
    {
        $segments = $request->segments();
        $segments = array_splice($segments, -4);

        return $this->parseSegments($segments);
    }

    /**
     * Parse the segments from the provided segments.
     *
     * @param  array $segments
     * @return \Peakfijn\GetSomeRest\Contracts\Url
     */
    protected function parseSegments(array $segments)
    {
        while ($segment = array_shift($segments)) {
            if ($this->isValidResource($segment)) {
                $this->resourceName = $segment;
                break;
            }
        }

        $this->resourceId = (count($segments) > 0) ? $segments[0] : null;
        $this->relationName = (count($segments) > 1) ? $segments[1] : null;
        $this->relationId = (count($segments) > 2) ? $segments[2] : null;

        return $this;
    }

    /**
     * Try and validate the resource name to the resource factory.
     *
     * @param  string $name
     * @return boolean
     */
    protected function isValidResource($name)
    {
        $factory = $this->resources;

        return $factory->contains($name) || $factory->resolve($name);
    }

    /**
     * Get the requested resource name.
     * According to the REST structure, this is required and may not be empty.
     * For example:
     *   - /v1/tags             => tags
     *   - /v1/items            => items
     *   - /v1/items/123        => items
     *   - /v1/tags/hello       => tags
     *   - /v1/items/123/tags   => items
     *   - /v1/items/123/tags/9 => items
     *
     * @return string
     */
    public function resourceName()
    {
        return $this->resourceName;
    }

    /**
     * Get the requested resource id.
     * It is not required and therefor may be null.
     * For example:
     *   - /v1/tags             => {null}
     *   - /v1/items            => {null}
     *   - /v1/items/123        => 123
     *   - /v1/tags/hello       => hello
     *   - /v1/items/123/tags   => 123
     *   - /v1/items/123/tags/9 => 123
     *
     * @return string|integer|null
     */
    public function resourceId()
    {
        return $this->resourceId;
    }

    /**
     * Get the resource relation.
     * This should be a method, the main resource contains.
     * Using the REST structure, it works like this:
     *   - /v1/tags             => {null}
     *   - /v1/items            => {null}
     *   - /v1/items/123        => {null}
     *   - /v1/tags/hello       => {null}
     *   - /v1/items/123/tags   => tags
     *   - /v1/items/123/tags/9 => tags
     *
     * @return string|null
     */
    public function relationName()
    {
        return $this->relationName;
    }

    /**
     * Get the resource relation id.
     * This is the identifier when scoping a related resource.
     * Using the REST structure, it works like this:
     *   - /v1/tags             => {null}
     *   - /v1/items            => {null}
     *   - /v1/items/123        => {null}
     *   - /v1/tags/hello       => {null}
     *   - /v1/items/123/tags   => {null}
     *   - /v1/items/123/tags/9 => 9
     *
     * @return string|integer|null
     */
    public function relationId()
    {
        return $this->relationId;
    }
}
