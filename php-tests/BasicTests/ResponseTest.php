<?php

namespace BasicTests;


use CommonTestClass;
use kalanis\RemoteRequestPsr\Content\Response;


class ResponseTest extends CommonTestClass
{
    public function testStatuses(): void
    {
        $lib = new Response();
        $this->assertEquals(0, $lib->getStatusCode());
        $this->assertEquals('KO', $lib->getReasonPhrase());
        $lib->withStatus(999, 'not-my-life');
        $this->assertEquals(999, $lib->getStatusCode());
        $this->assertEquals('not-my-life', $lib->getReasonPhrase());
    }
}
