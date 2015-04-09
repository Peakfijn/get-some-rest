<?php namespace Peakfijn\GetSomeRest\Rest;

use Peakfijn\GetSomeRest\Contracts\Dissector as DissectorContract;
use Peakfijn\GetSomeRest\Contracts\MethodFactory as MethodFactoryContract;
use Peakfijn\GetSomeRest\Contracts\Operator as OperatorContract;

class Operator implements OperatorContract
{
    /**
     * The dissector to extract from.
     *
     * @var \Peakfijn\GetSomeRest\Contracts\Dissector
     */
    protected $dissector;

    /**
     * The method factory to get the method logic from.
     *
     * @var \Peakfijn\GetSomeRest\Contracts\MethodFactory
     */
    protected $methods;

    /**
     * Create a new operator.
     *
     * @param \Peakfijn\GetSomeRest\Contracts\Dissector $dissector
     * @param \Peakfijn\GetSomeRest\Contracts\MethodFactory $methods
     */
    public function __construct(
        DissectorContract $dissector,
        MethodFactoryContract $methods
    ) {
        $this->dissector = $dissector;
        $this->methods = $methods;
    }

    /**
     * Execute the methods on the provided query.
     *
     * @param  mixed $resource
     * @return mixed
     */
    public function execute($resource)
    {
        $methods = $this->dissector->methods();

        foreach ($methods as $class => $value) {
            $method = $this->methods->make($class);

            if (!empty($method)) {
                $resource = $method->execute($value, $resource);
            }
        }

        return $resource;
    }
}
