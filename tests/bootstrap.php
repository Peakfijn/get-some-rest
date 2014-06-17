<?php

/*
|--------------------------------------------------------------------------
| Autoload
|--------------------------------------------------------------------------
|
| This package is installed through composer, using the composer.json file.
| It also manages the autoloading of all necessary files and classes.
| So let's include that first.
|
*/

require_once __DIR__ .'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Stubs
|--------------------------------------------------------------------------
|
| The Stubs classes are meant specially for testing. 
| These are example classes like the ResourceValidating(Class|Trait).
|
*/

require_once __DIR__ .'/Stubs.php';

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The TestCase class functions as the default test class.
| Within there you can define the basic, shared, functions.
| It is named after the Laravel TestCase class.
|
*/

require_once __DIR__ .'/TestCase.php';

/*
|--------------------------------------------------------------------------
| Encoder Test Case
|--------------------------------------------------------------------------
|
| Because every encoder is actually almost the same, we have created
| a default class with default tests that should pass for each encoder.
|
*/

require_once __DIR__ .'/EncoderTestCase.php';

/*
|--------------------------------------------------------------------------
| Mutator Test Case
|--------------------------------------------------------------------------
|
| Because every mutator is actually almost the same, we have created
| a default class with default tests that should pass for each mutator.
|
*/

require_once __DIR__ .'/MutatorTestCase.php';
