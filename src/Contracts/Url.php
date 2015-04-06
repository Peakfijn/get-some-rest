<?php namespace Peakfijn\GetSomeRest\Contracts;

use Illuminate\Http\Request;

interface Url
{
    /**
     * Parse the full request.
     * From the request, all information should be provided.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Peakfijn\GetSomeRest\Contracts\Url
     */
    public function parse(Request $request);

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
    public function resourceName();

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
    public function resourceId();

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
    public function relationName();

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
    public function relationId();
}
