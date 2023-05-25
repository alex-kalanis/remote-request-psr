<?php

namespace kalanis\RemoteRequestPsr\Content;


use kalanis\RemoteRequest\RequestException;
use Psr\Http\Message\StreamInterface;
use RuntimeException;


/**
 * Class Address
 * @package kalanis\RemoteRequestPsr\Content
 * Simple implementation of URI interface as address
 *
 * @todo: note: for immutability it might need a pair of classes
 *      - one for immutability with interface and other with stored data; but not now
 */
class Stream implements StreamInterface
{
    /** @var resource|null */
    protected $localStream = null;
    /** @var int */
    protected $offset = 0;

    /**
     * @param resource|string|int|float|null $source
     * @throws RequestException
     */
    public function __construct($source)
    {
        $this->localStream = is_resource($source) ? $source : $this->toStream($source);
    }

    /**
     * @param string|int|float|null $content
     * @throws RequestException
     * @return resource
     */
    protected function toStream($content)
    {
        $stream = fopen('php://temp', 'rb+');
        if (false === $stream) {
            // @codeCoverageIgnoreStart
            throw new RequestException('Problem with php internals');
        }
        // @codeCoverageIgnoreEnd
        fwrite($stream, strval($content));
        return $stream;
    }

    public function __toString(): string
    {
        try {
            return strval(stream_get_contents($this->getLocalStream(), -1, 0));
        } catch (RuntimeException $ex) {
            return '';
        }
    }

    public function close(): void
    {
        try {
            fclose($this->getLocalStream());
            $this->localStream = null;
            $this->offset = 0;
        } catch (RuntimeException $ex) {
            // nothing to do
        }
    }

    public function detach()
    {
        $stream = $this->localStream;
        $this->localStream = null;
        $this->offset = 0;
        return $stream;
    }

    public function getSize(): ?int
    {
        try {
            $data = fstat($this->getLocalStream());
            return (false !== $data) ? $data['size'] : null;
        } catch (RuntimeException $ex) {
            return null;
        }
    }

    public function tell(): int
    {
        return intval(ftell($this->getLocalStream()));
    }

    public function eof(): bool
    {
        return feof($this->getLocalStream());
    }

    public function isSeekable(): bool
    {
        $meta = stream_get_meta_data($this->getLocalStream());
        return $meta['seekable'];
    }

    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        fseek($this->getLocalStream(), $offset, $whence);
        $this->offset = $this->tell();
    }

    public function rewind(): void
    {
        $this->seek(0);
    }

    public function isWritable(): bool
    {
        $meta = stream_get_meta_data($this->getLocalStream());
        return (
            (false !== stripos($meta['mode'], 'w'))
            || (false !== stripos($meta['mode'], 'a'))
            || (false !== stripos($meta['mode'], 'x'))
            || (false !== stripos($meta['mode'], 'c'))
        );
    }

    public function write(string $string): int
    {
        return intval(fwrite($this->getLocalStream(), $string));
    }

    public function isReadable(): bool
    {
        $meta = stream_get_meta_data($this->getLocalStream());
        return (
            (false !== stripos($meta['mode'], 'r'))
            || (false !== stripos($meta['mode'], '+'))
        );
    }

    public function read(int $length): string
    {
        $data = strval(stream_get_contents($this->getLocalStream(), $length, $this->offset));
        $this->offset += strlen($data);
        return $data;
    }

    public function getContents(): string
    {
        $data = strval(stream_get_contents($this->getLocalStream(), -1, $this->offset));
        $this->offset += strlen($data);
        return $data;
    }

    public function getMetadata(?string $key = null)
    {
        try {
            $data = stream_get_meta_data($this->getLocalStream());
            if (!is_null($key)) {
                return (isset($data[$key])) ? $data[$key] : null;
            } else {
                return $data;
            }
        } catch (RuntimeException $ex) {
            return null;
        }
    }

    /**
     * @throws RuntimeException
     * @return resource
     */
    protected function getLocalStream()
    {
        if (empty($this->localStream)) {
            throw new RuntimeException('No stream available!');
        }
        return $this->localStream;
    }
}
