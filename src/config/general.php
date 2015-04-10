<?php

return [

    /*
    |----------------------------------------------------------------------
    | Namespace
    |----------------------------------------------------------------------
    |
    | The default namespace to search for resources. Note, this should be
    | equal to your applications name(space). If you have moved your
    | resources, please set the correct namespace.
    |
    */

    'namespace' => 'App',

    /*
    |----------------------------------------------------------------------
    | Resources Aliases
    |----------------------------------------------------------------------
    |
    | When you have some external, or not singular & camel cased, models
    | define an alias. For example:
    | [
    |   snake_cased => 'Project\Snake_cased'
    |   not-mine    => 'External\NotMine'
    | ]
    |
    */

    'resources' => [

    ],

    /*
    |----------------------------------------------------------------------
    | Methods
    |----------------------------------------------------------------------
    |
    | When requesting the API for data you sometimes find yourself thinking:
    |   - "Wow, I actually need the reverse of this..."
    |   - "Damn, I only need 5 of these..."
    |   - "It would be handy if I can include the relation in the response..."
    |
    | Well, these methods provides an easy way to add custom methods.
    | A method is actually a piece of code you execute on the query.
    | These methods are available through the query, prefixed to extract it from the normal queries.
    | For the "with" method you can add the "$with=relation" in the query.
    |
    */

    'methods' => [
        'with' => 'Peakfijn\GetSomeRest\Rest\Methods\WithMethod'
    ],

    /*
    |--------------------------------------------------------------------------
    | Encoders
    |--------------------------------------------------------------------------
    |
    | An API can have multiple front-end applications. Not all of them can talk
    | the same language. Therefore encoders are used to get the data into a
    | specific format. Here you can add your own encoders, or replace existing.
    |
    | The name should be equal to the general known file extension.
    |
    */

    'encoders' => [
        'json' => 'Peakfijn\GetSomeRest\Encoders\JsonEncoder',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Encoder
    |--------------------------------------------------------------------------
    |
    | When the user did not asked for a specific encoding, use the default.
    |
    */

    'default_encoder' => 'json',

    /*
    |--------------------------------------------------------------------------
    | Mutators
    |--------------------------------------------------------------------------
    |
    | APIs comes into different shapes and sizes. One simply returns an array,
    | others like to add some information about the collection. A mutator is
    | used to achieve these differences. A single mutator mutates, an array or
    | object, to the desired response format. Here you can implement your own.
    |
    */

    'mutators' => [
        'array' => 'Peakfijn\GetSomeRest\Mutators\ArrayMutator',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Mutator
    |--------------------------------------------------------------------------
    |
    | When the user did not asked for a specific mutation, use the default.
    |
    */

    'default_mutator' => 'array',

];
