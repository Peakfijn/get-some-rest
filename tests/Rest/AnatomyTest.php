<?php namespace Peakfijn\GetSomeRest\Tests\Rest;

use Peakfijn\GetSomeRest\Rest\Anatomy;
use Peakfijn\GetSomeRest\Tests\AbstractUnitTest;

class AnatomyTest extends AbstractUnitTest
{
    /**
     * Get a new instance of the relevant class.
     * All possible parameters MUST be optional.
     *
     * @return \Peakfijn\GetSomeRest\Rest\Anatomy
     */
    protected function getInstance()
    {
        return new Anatomy();
    }

    public function testWithResourceNameIsImmutable()
    {
        $anatomy = $this->getInstance();
        $filled = $anatomy->withResourceName('tested');

        $this->assertNotEquals($anatomy, $filled);
    }

    public function testHasResourceNameReturnsCorrectBoolean()
    {
        $anatomy = $this->getInstance();
        $filled = $anatomy->withResourceName('tested');

        $this->assertFalse($anatomy->hasResourceName());
        $this->assertTrue($filled->hasResourceName());
    }

    public function testGetResourceNameReturnsCorrectValue()
    {
        $anatomy = $this->getInstance();
        $filled = $anatomy->withResourceName('tested');

        $this->assertNull($anatomy->getResourceName());
        $this->assertEquals('tested', $filled->getResourceName());
    }

    public function testWithResourceIdIsImmutable()
    {
        $anatomy = $this->getInstance();
        $filled = $anatomy->withResourceId(123);

        $this->assertNotEquals($anatomy, $filled);
    }

    public function testHasResourceIdReturnsCorrectBoolean()
    {
        $anatomy = $this->getInstance();
        $filled = $anatomy->withResourceId(123);

        $this->assertFalse($anatomy->hasResourceId());
        $this->assertTrue($filled->hasResourceId());
    }

    public function testGetResourceIdReturnsCorrectValue()
    {
        $anatomy = $this->getInstance();
        $filled = $anatomy->withResourceId(123);

        $this->assertNull($anatomy->getResourceId());
        $this->assertEquals(123, $filled->getResourceId());
    }

    public function testWithRelationNameIsImmutable()
    {
        $anatomy = $this->getInstance();
        $filled = $anatomy->withRelationName('related');

        $this->assertNotEquals($anatomy, $filled);
    }

    public function testHasRelationNameReturnsCorrectBoolean()
    {
        $anatomy = $this->getInstance();
        $filled = $anatomy->withRelationName('related');

        $this->assertFalse($anatomy->hasRelationName());
        $this->assertTrue($filled->hasRelationName());
    }

    public function testGetRelationNameReturnsCorrectValue()
    {
        $anatomy = $this->getInstance();
        $filled = $anatomy->withRelationName('related');

        $this->assertNull($anatomy->getRelationName());
        $this->assertEquals('related', $filled->getRelationName());
    }

    public function testWithRelationIdIsImmutable()
    {
        $anatomy = $this->getInstance();
        $filled = $anatomy->withRelationId('abc');

        $this->assertNotEquals($anatomy, $filled);
    }

    public function testHasRelationIdReturnsCorrectBoolean()
    {
        $anatomy = $this->getInstance();
        $filled = $anatomy->withRelationId('abc');

        $this->assertFalse($anatomy->hasRelationId());
        $this->assertTrue($filled->hasRelationId());
    }

    public function testGetRelationIdReturnsCorrectValue()
    {
        $anatomy = $this->getInstance();
        $filled = $anatomy->withRelationId('abc');

        $this->assertNull($anatomy->getRelationId());
        $this->assertEquals('abc', $filled->getRelationId());
    }
}
