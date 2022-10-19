<?php

declare(strict_types=1);

namespace Wimski\Curl\Tests;

use CurlHandle;
use phpmock\phpunit\PHPMock;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Wimski\Curl\CurlError;
use Wimski\Curl\CurlResource;

class CurlResourceTest extends TestCase
{
    use PHPMock;

    protected CurlResource $resource;
    protected CurlHandle $handle;
    protected MockObject $curlSetopt;
    protected MockObject $curlSetoptArray;
    protected MockObject $curlExec;
    protected MockObject $curlClose;
    protected MockObject $curlError;
    protected MockObject $curlErrno;

    protected function setUpCurlMocks(): void
    {
        $this->curlSetopt      = $this->getFunctionMock('Wimski\Curl', 'curl_setopt');
        $this->curlSetoptArray = $this->getFunctionMock('Wimski\Curl', 'curl_setopt_array');
        $this->curlExec        = $this->getFunctionMock('Wimski\Curl', 'curl_exec');
        $this->curlClose       = $this->getFunctionMock('Wimski\Curl', 'curl_close');
        $this->curlError       = $this->getFunctionMock('Wimski\Curl', 'curl_error');
        $this->curlErrno       = $this->getFunctionMock('Wimski\Curl', 'curl_errno');

        $this->handle   = curl_init();
        $this->resource = new CurlResource($this->handle);
    }

    protected function expectCurlError(): void
    {
        self::expectException(CurlError::class);
        self::expectExceptionMessage('error');
        self::expectExceptionCode(123);

        $this->curlError
            ->expects(self::once())
            ->with($this->handle)
            ->willReturn('error');

        $this->curlErrno
            ->expects(self::once())
            ->willReturn($this->handle)
            ->willReturn(123);
    }

    /**
     * @test
     */
    public function it_sets_an_option(): void
    {
        $this->setUpCurlMocks();

        $this->curlSetopt
            ->expects(self::once())
            ->with($this->handle, 1, true)
            ->willReturn(true);

        $this->resource->setOption(1, true);
    }

    /**
     * @test
     */
    public function it_throws_an_exception_when_setting_an_option_failed(): void
    {
        $this->setUpCurlMocks();
        $this->expectCurlError();

        $this->curlSetopt
            ->expects(self::once())
            ->with($this->handle, 1, true)
            ->willReturn(false);

        $this->resource->setOption(1, true);
    }

    /**
     * @test
     */
    public function it_sets_options(): void
    {
        $this->setUpCurlMocks();

        $this->curlSetoptArray
            ->expects(self::once())
            ->with($this->handle, [1 => true])
            ->willReturn(true);

        $this->resource->setOptions([1 => true]);
    }

    /**
     * @test
     */
    public function it_throws_an_exception_when_setting_options_failed(): void
    {
        $this->setUpCurlMocks();
        $this->expectCurlError();

        $this->curlSetoptArray
            ->expects(self::once())
            ->with($this->handle, [1 => true])
            ->willReturn(false);

        $this->resource->setOptions([1 => true]);
    }

    /**
     * @test
     */
    public function it_executes_and_returns_a_string(): void
    {
        $this->setUpCurlMocks();

        $this->curlExec
            ->expects(self::once())
            ->with($this->handle)
            ->willReturn('foo');

        self::assertSame('foo', $this->resource->execute());
    }

    /**
     * @test
     */
    public function it_executes_and_returns_null(): void
    {
        $this->setUpCurlMocks();

        $this->curlExec
            ->expects(self::once())
            ->with($this->handle)
            ->willReturn(true);

        self::assertNull($this->resource->execute());
    }

    /**
     * @test
     */
    public function it_throws_an_exception_when_executing_failed(): void
    {
        $this->setUpCurlMocks();
        $this->expectCurlError();

        $this->curlExec
            ->expects(self::once())
            ->with($this->handle)
            ->willReturn(false);

        $this->resource->execute();
    }

    /**
     * @test
     */
    public function it_closes_the_handle(): void
    {
        self::expectError();
        self::expectErrorMessage('Typed property Wimski\Curl\CurlResource::$handle must not be accessed before initialization');

        $this->setUpCurlMocks();

        $this->curlClose
            ->expects(self::once())
            ->with($this->handle);

        $this->resource->close();

        $this->resource->getHandle();
    }

    /**
     * @test
     */
    public function it_returns_the_handle(): void
    {
        $this->setUpCurlMocks();

        self::assertSame($this->handle, $this->resource->getHandle());
    }

    /**
     * @test
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function it_makes_a_curl_request(): void
    {
        /** @var CurlHandle $handle */
        $handle = curl_init('file://' . realpath(__DIR__ . '/stubs/example.txt'));

        $resource = new CurlResource($handle);

        $resource->setOption(CURLOPT_RETURNTRANSFER, true);

        self::assertSame("Hello World!\n", $resource->execute());

        $resource->close();
    }
}
