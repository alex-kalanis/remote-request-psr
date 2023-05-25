<?php

namespace BasicTests;


use CommonTestClass;
use kalanis\RemoteRequestPsr\Content\Address;
use kalanis\RemoteRequestPsr\Content\Request;


class RequestTest extends CommonTestClass
{
    public function testTarget(): void
    {
        $lib = new Request();
        $this->assertEquals('', $lib->getRequestTarget());
        $lib->withRequestTarget('not-my-life');
        $this->assertEquals('not-my-life', $lib->getRequestTarget());
    }

    public function testMethod(): void
    {
        $lib = new Request();
        $this->assertEquals('GET', $lib->getMethod());
        $lib->withMethod('not-my-life');
        $this->assertEquals('not-my-life', $lib->getMethod());
    }

    public function testUri(): void
    {
        $url = new Address();
        $url->withHost('dummy')->withPort(23)->withQuery('foo=bar');
        $lib = new Request();
        $lib->withUri($url);
        $this->assertEquals('//dummy:23/?foo=bar', strval($lib->getUri()));
    }

    public function testUriFail(): void
    {
        $lib = new Request();
        $this->expectException(\RuntimeException::class);
        $lib->getUri();
    }
}
