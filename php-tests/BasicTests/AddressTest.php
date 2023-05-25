<?php

namespace BasicTests;


use CommonTestClass;
use InvalidArgumentException;
use kalanis\RemoteRequestPsr\Content\Address;


class AddressTest extends CommonTestClass
{
    public function testMethod(): void
    {
        $lib = new Address();
        $this->assertEquals('', $lib->getScheme());
        $lib->withScheme('not-my-life');
        $this->assertEquals('not-my-life', $lib->getScheme());
    }

    public function testHost(): void
    {
        $lib = new Address();
        $this->assertEquals('', $lib->getAuthority());
        $this->assertEquals('', $lib->getHost());
        $lib->withHost('not-my-life');
        $this->assertEquals('not-my-life', $lib->getHost());
    }

    public function testPort(): void
    {
        $lib = new Address();
        $this->assertEquals(null, $lib->getPort());
        $lib->withScheme('not-my-life');
        $this->assertEquals(80, $lib->getPort());
        $lib->withPort(777);
        $this->assertEquals(777, $lib->getPort());
        $lib->withPort(null);
        $this->assertEquals(80, $lib->getPort());
    }

    public function testPortFail1(): void
    {
        $lib = new Address();
        $this->expectException(InvalidArgumentException::class);
        $lib->withPort(99999);
    }

    public function testPortFail2(): void
    {
        $lib = new Address();
        $this->expectException(InvalidArgumentException::class);
        $lib->withPort(-1);
    }

    public function testPath(): void
    {
        $lib = new Address();
        $this->assertEquals('/', $lib->getPath());
        $lib->withPath('not-my-life');
        $this->assertEquals('not-my-life', $lib->getPath());
        $lib->withPath('');
        $this->assertEquals('/', $lib->getPath());
    }

    public function testQuery(): void
    {
        $lib = new Address();
        $this->assertEquals('', $lib->getQuery());
        $lib->withQuery('not-my-life');
        $this->assertEquals('not-my-life', $lib->getQuery());
        $lib->withQuery('');
        $this->assertEquals('', $lib->getQuery());
    }

    public function testFragment(): void
    {
        $lib = new Address();
        $this->assertEquals('', $lib->getFragment());
        $lib->withFragment('not-my-life');
        $this->assertEquals('not-my-life', $lib->getFragment());
        $lib->withFragment('');
        $this->assertEquals('', $lib->getFragment());
    }

    public function testUser(): void
    {
        $lib = new Address();
        $this->assertEquals('', $lib->getUserInfo());
        $lib->withUserInfo('someone');
        $this->assertEquals('someone', $lib->getUserInfo());
        $lib->withUserInfo('someone', 'nologin');
        $this->assertEquals('someone:nologin', $lib->getUserInfo());
        $lib->withUserInfo('');
        $this->assertEquals('', $lib->getUserInfo());
    }
}
