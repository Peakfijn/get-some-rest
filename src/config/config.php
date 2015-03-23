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
    | Resources - Routes Controller
    |----------------------------------------------------------------------
    |
    | Here you can define if you would like to use plural names of the resource
    | inside the url, or if you would like singular. For example:
    |
    |   /api/v1/resources => plural
    |   /api/v1/resource  => singular
    |
    | Note, you can only use one for the sake of consistency of your API.
    | We highly recommend to use plural, but in the end it's up to you.
    |
    */

    'plural' => true,

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
    ],

    /*
    |----------------------------------------------------------------------
    | Resources - Generate Routes
    |----------------------------------------------------------------------
    |
    | All resources must have it's own routes defined. This can be attached
    | to a controller or closure. When this is set the true, it will add a
    | special route that should cover all restful resource actions.
    |
    | The use of this is recommended for development, since you only have to
    | define and setup your resources. For production you have to take security
    | in account, else your API MIGHT be unsecure.
    |
    */

    'generate-routes' => true,

    /*
    |----------------------------------------------------------------------
    | Resources - Routes Settings
    |----------------------------------------------------------------------
    |
    | When generating the resource routes Get Some Rest will apply the code.
    | Here you can define some settings that will be passed to the router->group
    | method. You can set a prefix, add middleware or set other settings.
    |
    */

    'route-settings' => [
        'prefix' => 'api/v1'
    ],

    /*
    |----------------------------------------------------------------------
    | Resources - Routes Controller
    |----------------------------------------------------------------------
    |
    | When generating the resource routes Get Some Rest will apply the code.
    | Here you can define which controller it should use. Note that this
    | controller MUST discover the target resource by itself, not the URL.
    | This is because the resource name, in the url, is a variable and can be
    | different each call.
    |
    */

    'route-controller' => '\Peakfijn\GetSomeRest\Http\Controllers\RestController',

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
