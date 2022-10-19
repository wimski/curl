<?php

declare(strict_types=1);

namespace Wimski\Curl\Tests;

use phpmock\phpunit\PHPMock;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Wimski\Curl\CurlResource;
use Wimski\Curl\CurlResourceFactory;

class CurlResourceFactoryTest extends TestCase
{
    use PHPMock;

    protected CurlResourceFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = new CurlResourceFactory();
    }

    /**
     * @test
     */
    public function it_makes_a_curl_resource_with_an_url(): void
    {
        $resource = $this->factory->make('https://google.com');

        self::assertInstanceOf(CurlResource::class, $resource);

        $info = curl_getinfo($resource->getHandle());

        self::assertSame('https://google.com', $info['url']);
    }

    /**
     * @test
     */
    public function it_makes_a_curl_resource_without_an_url(): void
    {
        $resource = $this->factory->make();

        self::assertInstanceOf(CurlResource::class, $resource);

        $info = curl_getinfo($resource->getHandle());

        self::assertSame('', $info['url']);
    }

    /**
     * @test
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function it_throws_an_exception_when_a_curl_resource_cannot_be_made(): void
    {
        self::expectException(RuntimeException::class);
        self::expectExceptionMessage('Failed to initialize a cURL handle');

        $this->getFunctionMock('Wimski\Curl', 'curl_init')
            ->expects(self::once())
            ->with(null)
            ->willReturn(false);

        $this->factory->make();
    }
}
