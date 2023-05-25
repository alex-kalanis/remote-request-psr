<?php

namespace TraitsTests;


use CommonTestClass;
use kalanis\RemoteRequestPsr\Content\Stream;
use kalanis\RemoteRequestPsr\Traits\THeaders;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;


class HeadersTest extends CommonTestClass
{
    public function testPass(): void
    {
        $lib = new XHeaders();
        $this->assertEmpty($lib->getHeaders());
        $this->assertFalse($lib->hasHeader('not defined'));
        $this->assertEmpty($lib->getHeader('not defined'));
        $this->assertEmpty($lib->getHeaderLine('not defined'));
    }

    public function testSetGetSimple(): void
    {
        $lib = new XHeaders();
        $lib->withHeader('foo', 'bar');
        $lib->withHeader('fOO', 'baz');
        $this->assertEquals(['bar', 'baz'], $lib->getHeader('fOo'));
        $this->assertTrue($lib->hasHeader('Foo'));
        $lib->withAddedHeader('FOO', 'baz');
        $this->assertEquals(['baz'], $lib->getHeader('FOO'));
        $lib->withoutHeader('fOo');
        $this->assertEmpty($lib->getHeaders());
    }

    public function testSetGetArray(): void
    {
        $lib = new XHeaders();
        $lib->withHeader('foo', ['bar', 'baz']);
        $lib->withHeader('fOO', ['ijn', 'tfc']);
        $this->assertEquals(['bar', 'baz', 'ijn', 'tfc'], $lib->getHeader('fOo'));
        $lib->withAddedHeader('FOO', ['baf', 'gdg']);
        $this->assertEquals(['baf', 'gdg'], $lib->getHeader('FOO'));
    }
}


class XHeaders implements MessageInterface
{
    use THeaders;

    public function getProtocolVersion(): string
    {
        return 'mock';
    }

    public function withProtocolVersion(string $version): MessageInterface
    {
        return $this;
    }

    public function getBody(): StreamInterface
    {
        return new Stream('mock');
    }

    public function withBody(StreamInterface $body): MessageInterface
    {
        return $this;
    }
}
