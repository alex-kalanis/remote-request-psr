<?php

namespace TraitsTests;


use CommonTestClass;
use kalanis\RemoteRequestPsr\Content\Stream;
use kalanis\RemoteRequestPsr\Traits\TBody;
use Psr\Http\Message\MessageInterface;


class BodyTest extends CommonTestClass
{
    public function testBody(): void
    {
        $lib = new XBody();
        $lib->withBody(new Stream('yxcvbnmasdfghjklqwertzuiop'));
        $this->assertEquals('yxcvbnmasdfghjklqwertzuiop', $lib->getBody()->getContents());
    }

    public function testBodyFail(): void
    {
        $lib = new XBody();
        $this->expectException(\RuntimeException::class);
        $lib->getBody();
    }
}


class XBody implements MessageInterface
{
    use TBody;

    public function getProtocolVersion(): string
    {
        return '';
    }

    public function withProtocolVersion(string $version): MessageInterface
    {
        return $this;
    }

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
}
