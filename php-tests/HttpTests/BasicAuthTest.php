<?php

namespace HttpTests;


use CommonTestClass;
use kalanis\RemoteRequest\Protocols\Http;
use kalanis\RemoteRequest\RequestException;
use kalanis\RemoteRequestPsr\Content\Stream;
use kalanis\RemoteRequestPsr\Http\BasicAuth;


class BasicAuthTest extends CommonTestClass
{
    /**
     * @throws RequestException
     */
    public function testPost(): void
    {
        $lib = new BasicAuth();
        $lib->setCredentials('foo', 'bar');
        $lib->setContentStream(new Stream('abcdefghijklmnopqrstuvwxyz0123456789abcdefghijklmnopqrstuvwxyz0123456789abcdefghijklmnopqrstuvwxyz0123456789'));
        $this->assertEquals('POST / HTTP/1.1' . Http::DELIMITER
            . 'Host: ' . Http::DELIMITER
            . 'Accept: */*' . Http::DELIMITER
            . 'User-Agent: php-agent/1.3' . Http::DELIMITER
            . 'Connection: close' . Http::DELIMITER
            . 'Authorization: Basic Zm9vOmJhcg==' . Http::DELIMITER
            . 'Content-Length: 108' . Http::DELIMITER
            . 'Content-Type: application/x-www-form-urlencoded' . Http::DELIMITER
            . '' . Http::DELIMITER
            . 'abcdefghijklmnopqrstuvwxyz0123456789abcdefghijklmnopqrstuvwxyz0123456789abcdefghijklmnopqrstuvwxyz0123456789',
            stream_get_contents($lib->getData(), -1, 0));
    }
}
