<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Resources
    |--------------------------------------------------------------------------
    |
    | An API consists from multiple components, especially from resources. A
    | resource is a (data) model built to execute REST actions upon.
    |
    */

    'resources' => [

        /*
        |----------------------------------------------------------------------
        | Resources - Namespace
        |----------------------------------------------------------------------
        |
        | The default namespace to search for resources. Note, this should be
        | equal to your applications name(space). If you have moved your
        | resources, please set the correct namespace.
        |
        */

        'namespace' => '',

        /*
        |----------------------------------------------------------------------
        | Resources - Aliases
        |----------------------------------------------------------------------
        |
        | When you have some external, or not singular & camelcased, models
        | define an alias. For example:
        | [
        |   snake_cased => '\Project\Snake_cased'
        |   notmine     => '\Externa\NotMine'
        | ]
        |
        */

        'aliases' => [ ],

        /*
        |----------------------------------------------------------------------
        | Resources - Events
        |----------------------------------------------------------------------
        |
        | When an action is executed on a specific resource, an event is fired
        | automatically. The name of the event is defined as following:
        |
        |   {NAMESPACE}\Events\{RESOURCE}Is{ACTION}
        |
        | Here you can change the action name to your desired value.
        | For example, when requesting a single resource through a get action
        | this event is fired.
        |
        |   MyNamespace\Events\ResourceIsShowed
        |
        | Also, the resource itself is passed as event payload.
        |
        */

        'events' => [
            'index'     => 'indexed',
            'show'      => 'showed',
            'store'     => 'stored',
            'update'    => 'updated',
            'destroy'   => 'destroyed'
        ]
    ],

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
        'xml'  => '\Peakfijn\GetSomeRest\Encoders\XmlEncoder',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Encoder
    |--------------------------------------------------------------------------
    |
    | When the user did not asked for a specific encoding, use the default.
    |
    */

    'defaultEncoder' => 'json',

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

    'defaultMutator' => 'array',

];
