<?php

declare(strict_types=1);

namespace Wimski\Curl\Contracts;

use CurlHandle;
use Wimski\Curl\CurlError;

interface CurlResourceInterface
{
    /**
     * @param int   $option
     * @param mixed $value
     * @return CurlResourceInterface
     * @throws CurlError
     */
    public function setOption(int $option, mixed $value): CurlResourceInterface;

    /**
     * @param array<int, mixed> $options
     * @return CurlResourceInterface
     * @throws CurlError
     */
    public function setOptions(array $options): CurlResourceInterface;

    /**
     * @return string|null
     * @throws CurlError
     */
    public function execute(): ?string;

    public function close(): void;

    public function getHandle(): CurlHandle;
}
