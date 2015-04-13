# Get Some REST
_GSR_ is a simple, yet powerful, package that integrates into your Laravel 5 project.
It provides some tools to help you get started on your restful API with just some basic setup.
The best part of this is that you can extend or modify the core of the system, using an implementation of your own liking.

[![Latest Version](https://img.shields.io/packagist/v/peakfijn/get-some-rest.svg?style=flat-square)](https://packagist.org/packages/peakfijn/get-some-rest)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/travis/Peakfijn/get-some-rest.svg?style=flat-square)](https://travis-ci.org/Peakfijn/get-some-rest)
[![Coverage Status](https://img.shields.io/coveralls/Peakfijn/get-some-rest/develop.svg?style=flat-square)](https://coveralls.io/r/Peakfijn/get-some-rest)
[![Code Climate](https://img.shields.io/codeclimate/github/Peakfijn/get-some-rest.svg?style=flat-square)](https://codeclimate.com/github/Peakfijn/get-some-rest)
[![Total Downloads](https://img.shields.io/packagist/dt/peakfijn/get-some-rest.svg?style=flat-square)](https://packagist.org/packages/peakfijn/get-some-rest)

## Requirements
This package will work just fine with the following requirements.

- **PHP 5.4+**
- **Laravel 5+**

## Installation
> Get Some REST is currently in intensive development, meaning it is **not ready for production... _yet_...**

_GSR_ is best installed with [composer](https://getcomposer.org/), by adding it to your **require** section in **composer.json**. Run `composer update` afterwards to let composer download the required package.

```json
{
    "require": {
        "peakfijn/get-some-rest": "dev-develop"
    }
}
```

Or execute the following command inside your laravel 5 project.

```sh
composer require peakfijn/get-some-rest:dev-develop
```

## Register in Laravel
After the composer installation, we need to add it to the service provider of your Laravel application.
This can be done by adding the following line to the **/config/app.php**.

```php
'providers' => [

    /*
     * Laravel Framework Service Providers...
     */
    ...,

    /*
     * Application Service Providers...
     */
    ...,

    'Peakfijn\GetSomeRest\GetSomeRestServiceProvider',
],
```

> Please add this to **the bottom** of the array, else it will overwrite some functionalities resulting in unexpected behaviour.
