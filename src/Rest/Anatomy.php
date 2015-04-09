<?php namespace Peakfijn\GetSomeRest\Rest;

use Peakfijn\GetSomeRest\Contracts\Anatomy as AnatomyContract;

class Anatomy implements AnatomyContract
{
    /**
     * The resource name.
     *
     * @var string
     */
    protected $resourceName;

    /**
     * The resource id.
     *
     * @var string|integer|null
     */
    protected $resourceId;

    /**
     * The relation name.
     *
     * @var string|null
     */
    protected $relationName;

    /**
     * The relation id.
     *
     * @var string|integer|null
     */
    protected $relationId;

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
    public function getResourceName()
    {
        return $this->resourceName;
    }

    /**
     * Set the resource name for the anatomy.
     * This MUST be implemented to retain the immutability of this class.
     *
     * @param  string $name
     * @return \Peakfijn\GetSomeRest\Contracts\Anatomy
     */
    public function withResourceName($name)
    {
        $new = clone $this;
        $new->resourceName = $name;

        return $new;
    }

    /**
     * Check if the anatomy has a resource name defined.
     *
     * @return boolean
     */
    public function hasResourceName()
    {
        return !empty($this->resourceName);
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
    public function getResourceId()
    {
        return $this->resourceId;
    }

    /**
     * Set the resource id for this anatomy.
     * This MUST be implemented to retain the immutability of this class.
     *
     * @param  string|integer|null $id
     * @return \Peakfijn\GetSomeRest\Contracts\Anatomy
     */
    public function withResourceId($id)
    {
        $new = clone $this;
        $new->resourceId = $id;

        return $new;
    }

    /**
     * Check if the anatomy has a resource id defined.
     *
     * @return boolean
     */
    public function hasResourceId()
    {
        return !empty($this->resourceId);
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
    public function getRelationName()
    {
        return $this->relationName;
    }

    /**
     * Set the relation name for this anatomy.
     * This MUST be implemented to retain the immutability of this class.
     *
     * @param  string|null $name
     * @return \Peakfijn\GetSomeRest\Contracts\Anatomy
     */
    public function withRelationName($name)
    {
        $new = clone $this;
        $new->relationName = $name;

        return $new;
    }

    /**
     * Check if the anatomy has a relation name defined.
     *
     * @return boolean
     */
    public function hasRelationName()
    {
        return !empty($this->relationName);
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
    public function getRelationId()
    {
        return $this->relationId;
    }

    /**
     * Set the relation id for this anatomy.
     * This MUST be implemented to retain the immutability of this class.
     *
     * @param  string|integer|null $id
     * @return \Peakfijn\GetSomeRest\Contracts\Anatomy
     */
    public function withRelationId($id)
    {
        $new = clone $this;
        $new->relationId = $id;

        return $new;
    }

    /**
     * Check if the anatomy has a relation id defined.
     *
     * @return boolean
     */
    public function hasRelationId()
    {
        return !empty($this->relationId);
    }

    /**
     * Check if the response should be a collection or just a single response.
     *
     * @return boolean
     */
    public function shouldBeCollection()
    {
        return !$this->hasResourceId() || ($this->hasRelationName() && !$this->hasRelationId());
    }
}
