<?php

namespace BasicTests;


use CommonTestClass;
use kalanis\RemoteRequest\RequestException;
use kalanis\RemoteRequestPsr\Content\Stream;


class StreamTest extends CommonTestClass
{
    /**
     * @throws RequestException
     */
    public function testRead1(): void
    {
        $lib = $this->getLib1();
        $this->assertTrue($lib->isReadable());
        $this->assertTrue($lib->isSeekable());
        $content = '';
        while (!$lib->eof()) {
            $content .= $lib->read(10);
        }
        $this->assertEquals('abcdefghijklmnopqrstuvwxyz0123456789abcdefghijklmnopqrstuvwxyz0123456789abcdefghijklmnopqrstuvwxyz0123456789', $content);
        $lib->close();
    }

    /**
     * @throws RequestException
     */
    public function testRead2(): void
    {
        $lib = $this->getLib2();
        $this->assertTrue($lib->isReadable());
        $this->assertTrue($lib->isSeekable());
        $this->assertEquals('abcdefghijklmnopqrstuvwxyz0123456789abcdefghijklmnopqrstuvwxyz0123456789abcdefghijklmnopqrstuvwxyz0123456789', strval($lib));
        $lib->close();
        $lib->close();
    }

    /**
     * @throws RequestException
     */
    public function testReadDetach1(): void
    {
        $lib = $this->getLib2();
        $lib->detach();
        $this->expectException(\RuntimeException::class);
        $lib->read(15);
    }

    /**
     * @throws RequestException
     */
    public function testReadDetach2(): void
    {
        $lib = $this->getLib2();
        $lib->detach();
        $this->expectException(\RuntimeException::class);
        $lib->getContents();
    }

    /**
     * @throws RequestException
     */
    public function testReadDetach3(): void
    {
        $lib = $this->getLib1();
        $lib->detach();
        $this->assertEmpty(strval($lib));
    }

    /**
     * @throws RequestException
     */
    public function testPosition(): void
    {
        $lib = $this->getLib2();
        $lib->read(55);
        $this->assertEquals(55, $lib->tell());
        $this->assertEquals('tuvwxyz0123456789abcdefghijklmnopqrstuvwxyz0123456789', $lib->getContents());
        $lib->seek(72);
        $lib->rewind();
        $lib->close();
        $this->expectException(\RuntimeException::class);
        $lib->tell();
    }

    /**
     * @throws RequestException
     */
    public function testSize(): void
    {
        $lib = $this->getLib1();
        $this->assertEquals(108, $lib->getSize());
        $lib->close();
        $this->assertNull($lib->getSize());
    }

    /**
     * @throws RequestException
     */
    public function testMeta(): void
    {
        $lib = $this->getLib1();
        $this->assertNull($lib->getMetadata('not set'));
        $this->assertEquals('w+b', $lib->getMetadata('mode'));
        $this->assertEquals([
            'wrapper_type' => 'PHP',
            'stream_type' => 'TEMP',
            'mode' => 'w+b',
            'unread_bytes' => 0,
            'seekable' => true,
            'uri' => 'php://temp',
        ], $lib->getMetadata());
        $lib->close();
        $this->assertNull($lib->getMetadata());
    }

    public function testWrite(): void
    {
        $lib = new Stream('');
        $this->assertTrue($lib->isWritable());
        $lib->write('yxcvbnmasdfghjklqwertzuiop');
        $lib->rewind();
        $this->assertEquals('yxcvbnmasdfghjklqwertzuiop', $lib->getContents());
    }

    /**
     * @throws RequestException
     * @return Stream
     */
    protected function getLib1(): Stream
    {
        return new Stream('abcdefghijklmnopqrstuvwxyz0123456789abcdefghijklmnopqrstuvwxyz0123456789abcdefghijklmnopqrstuvwxyz0123456789');
    }

    /**
     * @throws RequestException
     * @return Stream
     */
    protected function getLib2(): Stream
    {
        $h = fopen('php://memory', 'rb+');
        fwrite($h, 'abcdefghijklmnopqrstuvwxyz0123456789abcdefghijklmnopqrstuvwxyz0123456789abcdefghijklmnopqrstuvwxyz0123456789');
        return new Stream($h);
    }
}
