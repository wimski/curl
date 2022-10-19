<?php

declare(strict_types=1);

namespace Wimski\Curl;

use RuntimeException;
use Wimski\Curl\Contracts\CurlResourceFactoryInterface;
use Wimski\Curl\Contracts\CurlResourceInterface;

class CurlResourceFactory implements CurlResourceFactoryInterface
{
    public function make(?string $url = null): CurlResourceInterface
    {
        $curlHandle = curl_init($url);

        if ($curlHandle === false) {
            throw new RuntimeException('Failed to initialize a cURL handle');
        }

        return new CurlResource($curlHandle);
    }
}
