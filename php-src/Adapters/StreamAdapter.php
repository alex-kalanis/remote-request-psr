<?php

namespace kalanis\RemoteRequestPsr\Adapters;


use kalanis\RemoteRequest\RequestException;
use kalanis\RemoteRequestPsr\Http\Answer;
use Psr\Http\Message\StreamInterface;
use RuntimeException;


/**
 * Class StreamAdapter
 * @package kalanis\RemoteRequestPsr\Adapters
 * Adapter of body of HTTP response
 * Returns workable stream under interface
 */
class StreamAdapter implements StreamInterface
{
    /** @var resource|null */
    protected $localStream = null;
    /** @var int */
    protected $offset = 0;

    /**
     * @param Answer $answer
     * @throws RequestException
     */
    public function __construct(Answer $answer)
    {
        $content = $answer->getBody();
        $this->localStream = is_resource($content) ? $content : $this->toStream($content);
    }

    /**
     * @param string|int|float|bool|null $content
     * @throws RequestException
     * @return resource
     * @codeCoverageIgnore if Answer passes string and not stream
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
        $data = fstat($this->getLocalStream());
        return (false !== $data) ? $data['size'] : null;
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
        return !empty($this->localStream);
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
        return false;
    }

    public function write(string $string): int
    {
        throw new RuntimeException('Cannot write into output stream.');
    }

    public function isReadable(): bool
    {
        return !empty($this->localStream);
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
