<?php

declare(strict_types=1);

namespace Wimski\Curl;

use CurlHandle;
use Wimski\Curl\Contracts\CurlResourceInterface;

class CurlResource implements CurlResourceInterface
{
    public function __construct(
        protected CurlHandle $handle,
    ) {
    }

    public function setOption(int $option, mixed $value): CurlResourceInterface
    {
        if (curl_setopt($this->handle, $option, $value) === false) {
            throw $this->makeCurlError();
        }

        return $this;
    }

    public function setOptions(array $options): CurlResourceInterface
    {
        if (curl_setopt_array($this->handle, $options) === false) {
            throw $this->makeCurlError();
        }

        return $this;
    }

    public function execute(): ?string
    {
        $result = curl_exec($this->handle);

        if ($result === false) {
            throw $this->makeCurlError();
        }

        if (is_string($result)) {
            return $result;
        }

        return null;
    }

    public function close(): void
    {
        curl_close($this->handle);

        unset($this->handle);
    }

    public function getHandle(): CurlHandle
    {
        return $this->handle;
    }

    protected function makeCurlError(): CurlError
    {
        return new CurlError(
            curl_error($this->handle),
            curl_errno($this->handle),
        );
    }
}
