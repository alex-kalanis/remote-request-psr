<?php

namespace HttpTests;


use CommonTestClass;
use kalanis\RemoteRequest\Protocols\Http;
use kalanis\RemoteRequest\RequestException;
use kalanis\RemoteRequestPsr\Content\Stream;
use kalanis\RemoteRequestPsr\Http\Query;


class QueryTest extends CommonTestClass
{
    /**
     * @throws RequestException
     */
    public function testPost(): void
    {
        $lib = new Query();
        $lib->setContentStream(new Stream('abcdefghijklmnopqrstuvwxyz0123456789abcdefghijklmnopqrstuvwxyz0123456789abcdefghijklmnopqrstuvwxyz0123456789'));
        $lib->addValue('none', 'nope');
        $lib->addValues( ['none' => 'nope']);
        $lib->removeValue('unknown');
        $this->assertEquals('POST / HTTP/1.1' . Http::DELIMITER
            . 'Host: ' . Http::DELIMITER
            . 'Accept: */*' . Http::DELIMITER
            . 'User-Agent: php-agent/1.3' . Http::DELIMITER
            . 'Connection: close' . Http::DELIMITER
            . 'Content-Length: 108' . Http::DELIMITER
            . 'Content-Type: application/x-www-form-urlencoded' . Http::DELIMITER
            . '' . Http::DELIMITER
            . 'abcdefghijklmnopqrstuvwxyz0123456789abcdefghijklmnopqrstuvwxyz0123456789abcdefghijklmnopqrstuvwxyz0123456789',
            stream_get_contents($lib->getData(), -1, 0));
    }

    /**
     * @throws RequestException
     */
    public function testDelete(): void
    {
        $lib = new Query();
        $lib->setPath('/item');
        $lib->setMethod('delete');
        $this->assertEquals('DELETE /item HTTP/1.1' . Http::DELIMITER
            . 'Host: ' . Http::DELIMITER
            . 'Accept: */*' . Http::DELIMITER
            . 'User-Agent: php-agent/1.3' . Http::DELIMITER
            . 'Connection: close' . Http::DELIMITER
            . '' . Http::DELIMITER
            ,
            stream_get_contents($lib->getData(), -1, 0));
    }
}
