<?php

namespace TraitsTests;


use CommonTestClass;
use kalanis\RemoteRequestPsr\Content\Stream;
use kalanis\RemoteRequestPsr\Traits\TProtocol;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;


class ProtocolTest extends CommonTestClass
{
    public function testProtocol(): void
    {
        $lib = new XProtocol();
        $this->assertEquals('1.0', $lib->getProtocolVersion());
        $lib->withProtocolVersion('2.5');
        $this->assertEquals('2.5', $lib->getProtocolVersion());
        $lib->withProtocolVersion('not-a-numbers');
        $this->assertEquals('2.5', $lib->getProtocolVersion());
    }
}


class XProtocol implements MessageInterface
{
    use TProtocol;

    public function getHeaders(): array
    {
        return [];
    }

    public function hasHeader(string $name): bool
    {
        return false;
    }

    public function getHeader(string $name): array
    {
        return [];
    }

    public function getHeaderLine(string $name): string
    {
        return '';
    }

    public function withHeader(string $name, $value): MessageInterface
    {
        return $this;
    }

    public function withAddedHeader(string $name, $value): MessageInterface
    {
        return $this;
    }

    public function withoutHeader(string $name): MessageInterface
    {
        return $this;
    }

    public function getBody(): StreamInterface
    {
        return new Stream('');
    }

    public function withBody(StreamInterface $body): MessageInterface
    {
        return $this;
    }
}
