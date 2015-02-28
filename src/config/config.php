<?php

return [

    'encoders'       => [
        'json' => '\Peakfijn\GetSomeRest\Encoders\JsonEncoder',
        'xml'  => '\Peakfijn\GetSomeRest\Encoders\XmlEncoder'
    ],
    'defaultEncoder' => 'json',
    'mutator'        => '\Peakfijn\GetSomeRest\Mutators\PlainMutator'

];