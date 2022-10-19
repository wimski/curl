[![PHPStan](https://github.com/wimski/curl/actions/workflows/phpstan.yml/badge.svg)](https://github.com/wimski/curl/actions/workflows/phpstan.yml)
[![PHPUnit](https://github.com/wimski/curl/actions/workflows/phpunit.yml/badge.svg)](https://github.com/wimski/curl/actions/workflows/phpunit.yml)
[![Coverage Status](https://coveralls.io/repos/github/wimski/curl/badge.svg?branch=master)](https://coveralls.io/github/wimski/curl?branch=master)
[![Latest Stable Version](http://poser.pugx.org/wimski/curl/v)](https://packagist.org/packages/wimski/curl)

# cURL

A simple wrapper for cURL to use with DI and OOP.

* [Changelog](#changelog)
* [Install](#install)
* [Usage](#usage)

## Changelog

[View the changelog.](./CHANGELOG.md)

## Install

```bash
composer require wimski/curl
```

## Usage

```php
use Wimski\Curl\CurlResourceFactory;

$curlResourceFactory = new CurlResourceFactory();

$curlResource = $curlResourceFactory->make('https://some-webserver.com/resource-to-request');

$response = $curlResource
    ->setOption(CURLOPT_RETURNTRANSFER, true)
    ->execute();
    
$curlResource->close();
```

Ideally you would set up a singleton binding for the factory in your framework's container and use DI.

```php
use Wimski\Curl\Contracts\CurlResourceFactoryInterface;

class MyClass
{
    public function __construct(
        protected CurlResourceFactoryInterface $curlResourceFactory,
    ) {
    }
    
    public function getData(): string
    {
        $curlResource = $this->curlResourceFactory->make('https://some-webserver.com/resource-to-request');

        $response = $curlResource
            ->setOption(CURLOPT_RETURNTRANSFER, true)
            ->execute();
            
        $curlResource->close();
        
        return $response;
    }
}
```