<?php

return [

    /*
    |----------------------------------------------------------------------
    | Resource - Namespace
    |----------------------------------------------------------------------
    |
    | The default namespace to search for resources. Note, this should be
    | equal to your applications name(space). If you have moved your
    | resources, please set the correct namespace.
    |
    */

    'namespace' => '',

    /*
    |--------------------------------------------------------------------------
    | Encoders
    |--------------------------------------------------------------------------
    |
    | An API can have multiple front-end applications. Not all of them can talk
    | the same language. Therefore encoders are used to get the data into a
    | specific format. Here you can add your own encoders, or replace existings.
    |
    | The name should be equal to the general known file extension.
    |
    */

    'encoders' => [
        'json' => '\Peakfijn\GetSomeRest\Encoders\JsonEncoder',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Encoder
    |--------------------------------------------------------------------------
    |
    | When the user did not asked for a specific encoding, use the default.
    |
    */

    'default-encoder' => 'json',

    /*
    |--------------------------------------------------------------------------
    | Mutators
    |--------------------------------------------------------------------------
    |
    | APIs comes into different shapes and sizes. One simply returns an array,
    | others like to add some information about the collection. A mutator is
    | used to achief these differences. A single mutator mutates, an array or
    | object, to the desired response format. Here you can implement your own.
    |
    */

    'mutators' => [
        'array' => '\Peakfijn\GetSomeRest\Mutators\ArrayMutator',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Mutator
    |--------------------------------------------------------------------------
    |
    | When the user did not asked for a specific mutation, use the default.
    |
    */

    'default-mutator' => 'array',

];
