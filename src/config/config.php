<?php

return [

    'encoders'       => [
        'json' => '\Peakfijn\GetSomeRest\Encoders\JsonEncoder',
        'xml'  => '\Peakfijn\GetSomeRest\Encoders\XmlEncoder'
    ],
    'defaultEncoder' => 'json',
    'mutators'       => [
        'plain' => '\Peakfijn\GetSomeRest\Mutators\PlainMutator',
        'meta'  => '\Peakfijn\GetSomeRest\Mutators\MetaMutator'
    ]

];