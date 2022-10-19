<?php

declare(strict_types=1);

namespace Wimski\Curl\Contracts;

use RuntimeException;

interface CurlResourceFactoryInterface
{
    /**
     * @param string|null $url
     * @return CurlResourceInterface
     * @throws RuntimeException
     */
    public function make(?string $url = null): CurlResourceInterface;
}
