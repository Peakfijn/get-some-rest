<?php namespace Peakfijn\GetSomeRest\Contracts\Rest;

interface Anatomy
{
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
    public function getResourceName();

    /**
     * Set the resource name for the anatomy.
     * This MUST be implemented to retain the immutability of this class.
     *
     * @param  string $name
     * @return \Peakfijn\GetSomeRest\Contracts\Anatomy
     */
    public function withResourceName($name);

    /**
     * Check if the anatomy has a resource name defined.
     *
     * @return boolean
     */
    public function hasResourceName();

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
    public function getResourceId();

    /**
     * Set the resource id for this anatomy.
     * This MUST be implemented to retain the immutability of this class.
     *
     * @param  string|integer|null $id
     * @return \Peakfijn\GetSomeRest\Contracts\Anatomy
     */
    public function withResourceId($id);

    /**
     * Check if the anatomy has a resource id defined.
     *
     * @return boolean
     */
    public function hasResourceId();

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
    public function getRelationName();

    /**
     * Set the relation name for this anatomy.
     * This MUST be implemented to retain the immutability of this class.
     *
     * @param  string|null $name
     * @return \Peakfijn\GetSomeRest\Contracts\Anatomy
     */
    public function withRelationName($name);

    /**
     * Check if the anatomy has a relation name defined.
     *
     * @return boolean
     */
    public function hasRelationName();

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
    public function getRelationId();

    /**
     * Set the relation id for this anatomy.
     * This MUST be implemented to retain the immutability of this class.
     *
     * @param  string|integer|null $id
     * @return \Peakfijn\GetSomeRest\Contracts\Anatomy
     */
    public function withRelationId($id);

    /**
     * Check if the anatomy has a relation id defined.
     *
     * @return boolean
     */
    public function hasRelationId();

    /**
     * Check if the response should be a collection or just a single response.
     *
     * @return boolean
     */
    public function shouldBeCollection();
}
