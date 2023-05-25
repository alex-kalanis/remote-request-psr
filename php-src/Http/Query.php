<?php

namespace kalanis\RemoteRequestPsr\Http;


use kalanis\RemoteRequest\Protocols;
use kalanis\RemoteRequest\Protocols\Http;
use Psr\Http\Message\StreamInterface;


/**
 * Class Query
 * @package kalanis\RemoteRequestPsr\Http
 * Message to the remote server compilation - protocol http, uses StreamInterface as body
 */
class Query extends Protocols\Http\Query
{
    /** @var string */
    protected $path = '/';
    /** @var StreamInterface|null */
    protected $streamBody = null;

    public function setContentStream(StreamInterface $stream): self
    {
        $this->streamBody = $stream;
        return $this;
    }

    /**
     * Add HTTP variables
     * @param string[] $array
     * @return $this
     */
    public function addValues($array): parent
    {
        return $this;
    }

    /**
     * Add HTTP variable
     * @param string $key
     * @param string|Http\Query\Value $value
     * @return $this
     */
    public function addValue(string $key, $value): parent
    {
        return $this;
    }

    /**
     * Remove HTTP key-value
     * @param string $key
     * @return $this
     */
    public function removeValue(string $key): parent
    {
        return $this;
    }

    public function getData()
    {
        $this->contentStream = Protocols\Helper::getTempStorage();
        $this->contentLength = 0;
        $this->addHeader('Host', $this->getHostAndPort());

        $this->checkForMethod();
        $this->checkForFiles();
        $this->prepareBoundary();
        $this->prepareQuery();

        $this->contentLengthHeader();
        $this->contentTypeHeader();

        $storage = Protocols\Helper::getTempStorage();
        fwrite($storage, $this->renderRequestHeader());
        rewind($this->contentStream);
        stream_copy_to_stream($this->contentStream, $storage);
        rewind($storage);
        return $storage;
    }

    protected function prepareQuery(): parent
    {
        $this->contentLength += $this->addStreamBody();
        return $this;
    }

    protected function contentTypeHeader(): parent
    {
        if (in_array($this->getMethod(), $this->multipartMethods)) {
            $this->addHeader('Content-Type', 'application/x-www-form-urlencoded');
        } else {
            $this->removeHeader('Content-Type');
        }
        return $this;
    }

    protected function addStreamBody(): int
    {
        if (empty($this->streamBody)) {
            return 0;
        }
        $this->streamBody->rewind();
        $size = 0;
        while (!$this->streamBody->eof()) {
            $size += intval(fwrite($this->contentStream, $this->streamBody->read($this->segmentSize())));
        }
        return $size;
    }

    protected function segmentSize(): int
    {
        return 131072;
    }
}
