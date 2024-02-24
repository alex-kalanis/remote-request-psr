<?php

namespace AdaptersTests;


use CommonTestClass;
use kalanis\RemoteRequest\Protocols\Helper;
use kalanis\RemoteRequest\Protocols\Http;
use kalanis\RemoteRequest\RequestException;
use kalanis\RemoteRequestPsr\Adapters\StreamAdapter;
use kalanis\RemoteRequestPsr\Http\Answer;


class StreamTest extends CommonTestClass
{
    /**
     * @throws RequestException
     */
    public function testRead1(): void
    {
        $lib = $this->getLib();
        $this->assertTrue($lib->isReadable());
        $this->assertEquals(153, $lib->getSize());
        $content = '';
        while (!$lib->eof()) {
            $content .= $lib->read(10);
        }
        $this->assertEquals('<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Error</title>
  </head>
  <body>
    <h1>401 Unauthorized.</h1>
  </body>
</html>', $content);
        $lib->close();
        $lib->close();
    }

    /**
     * @throws RequestException
     */
    public function testRead2(): void
    {
        $lib = $this->getLib();
        $this->assertEquals('<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Error</title>
  </head>
  <body>
    <h1>401 Unauthorized.</h1>
  </body>
</html>', strval($lib));
        $lib->detach();
        $lib->detach();
    }

    /**
     * @throws RequestException
     */
    public function testRead3(): void
    {
        $lib = $this->getLib();
        $this->assertEquals('<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-', $lib->read(55));
        $this->assertEquals('8" />
    <title>Error</title>
  </head>
  <body>
    <h1>401 Unauthorized.</h1>
  </body>
</html>', $lib->getContents());
    }

    /**
     * @throws RequestException
     */
    public function testRead4(): void
    {
        $lib = $this->getLib();
        $lib->close();
        $this->assertEmpty(strval($lib));
    }

    /**
     * @throws RequestException
     */
    public function testSeek(): void
    {
        $lib = $this->getLib();
        $this->assertTrue($lib->isSeekable());
        $lib->seek(55);
        $this->assertEquals(55, $lib->tell());
        $lib->rewind();
        $lib->close();
        $this->assertFalse($lib->isSeekable());
    }

    /**
     * @throws RequestException
     */
    public function testMeta(): void
    {
        $lib = $this->getLib();
        $this->assertNull($lib->getMetadata('not set'));
        $this->assertEquals('w+b', $lib->getMetadata('mode'));
        $this->assertEquals([
            'wrapper_type' => 'PHP',
            'stream_type' => 'MEMORY',
            'mode' => 'w+b',
            'unread_bytes' => 0,
            'seekable' => true,
            'uri' => 'php://memory',
            'timed_out' => false,
            'blocked' => true,
            'eof' => false,
        ], $lib->getMetadata());
        $lib->close();
        $this->assertNull($lib->getMetadata());
    }

    /**
     * @throws RequestException
     */
    public function testWrite(): void
    {
        $lib = $this->getLib();
        $this->assertFalse($lib->isWritable());
        $this->expectException(\RuntimeException::class);
        $lib->write('okmijnuhbzgvtfcrdxesy');
    }

    /**
     * @throws RequestException
     */
    public function testStream(): void
    {
        $answer = new XAnswer();
        $answer->fillBodyStream('ijnuhbzgvtfcrdxesy');
        $lib = new StreamAdapter($answer);
        $this->assertEquals('ijnuhbzgvtfcrdxesy', $lib->getContents());
    }

    /**
     * @throws RequestException
     */
    public function testString(): void
    {
        $answer = new XAnswer();
        $answer->fillBodyString('ijnuhbzgvtfcrdxesy');
        $lib = new StreamAdapter($answer);
        $this->assertEquals('ijnuhbzgvtfcrdxesy', $lib->getContents());
    }

    /**
     * @throws RequestException
     * @return StreamAdapter
     */
    protected function getLib(): StreamAdapter
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
        return new StreamAdapter($content);
    }
}


class XAnswer extends Answer
{
    public function fillBodyStream(string $body = ''): void
    {
        $res = Helper::getTempStorage();
        fwrite($res, $body);
        rewind($res);
        $this->body = $res;
    }

    public function fillBodyString(string $body = ''): void
    {
        $this->body = $body;
    }
}
