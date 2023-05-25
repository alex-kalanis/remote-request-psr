<?php

namespace AdaptersTests;


use CommonTestClass;
use kalanis\RemoteRequest\Protocols\Http;
use kalanis\RemoteRequest\RequestException;
use kalanis\RemoteRequestPsr\Adapters\ResponseAdapter;
use kalanis\RemoteRequestPsr\Content\Stream;
use kalanis\RemoteRequestPsr\Http\Answer;


class ResponseTest extends CommonTestClass
{
    /**
     * @throws RequestException
     */
    public function testData(): void
    {
        $lib = $this->getLib();
        $this->assertEquals(401, $lib->getStatusCode());
        $this->assertEquals('Unauthorized', $lib->getReasonPhrase());
        $this->assertEquals('1.1', $lib->getProtocolVersion());  // always!
        $this->assertEquals([
            'Server' => ['PhpUnit/9.3.0'],
            'Date' => ['Sun, 10 Apr 2022 20:26:47 GMT'],
            'Content-Type' => ['text/html'],
            'Content-Length' => ['153'],
        ], $lib->getHeaders());
    }

    /**
     * @throws RequestException
     */
    public function testSetters(): void
    {
        $lib = $this->getLib();
        $lib->withProtocolVersion('any');
        $this->assertEquals('1.1', $lib->getProtocolVersion());
        $lib->withStatus(999, 'none');
        $this->assertEquals(401, $lib->getStatusCode());
        $this->assertEquals('Unauthorized', $lib->getReasonPhrase());
        $lib->withHeader('foo', 'bar');
        $lib->withAddedHeader('foo', 'baz');
        $lib->withoutHeader('boo');
        $this->assertEquals([
            'Server' => ['PhpUnit/9.3.0'],
            'Date' => ['Sun, 10 Apr 2022 20:26:47 GMT'],
            'Content-Type' => ['text/html'],
            'Content-Length' => ['153'],
        ], $lib->getHeaders());
        $this->assertTrue($lib->hasHeader('date'));
        $this->assertFalse($lib->hasHeader('none'));
        $this->assertEquals('text/html', $lib->getHeaderLine('content-type'));
        $lib->withBody(new Stream(''));
        $this->assertEquals('<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Error</title>
  </head>
  <body>
    <h1>401 Unauthorized.</h1>
  </body>
</html>', strval($lib->getBody()));
    }

    /**
     * @throws RequestException
     * @return ResponseAdapter
     */
    protected function getLib(): ResponseAdapter
    {
        $content = new Answer();
        $content->setResponse('HTTP/0.1 401 Unauthorized' . Http::DELIMITER
            . 'Server: PhpUnit/9.3.0' . Http::DELIMITER
            . 'Date: Sun, 10 Apr 2022 20:26:47 GMT' . Http::DELIMITER
            . 'Content-Type: text/html' . Http::DELIMITER
            . 'Content-Length: 153' . Http::DELIMITER
            . Http::DELIMITER
            . '<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Error</title>
  </head>
  <body>
    <h1>401 Unauthorized.</h1>
  </body>
</html>');
        return new ResponseAdapter($content);
    }

}
