<?php

namespace kalanis\RemoteRequestPsr\Traits;


use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;
use RuntimeException;


/**
 * Trait TBody
 * @package kalanis\RemoteRequestPsr\Traits
 * Note: Contrary the PSR requirements this class does not offer immutability
 * That's because you need to change request properties before you set them to RemoteRequest
 */
trait TBody
{
    protected ?StreamInterface $body = null;

    public function getBody(): StreamInterface
    {
        if (empty($this->body)) {
            throw new RuntimeException('You must set the body first!');
        }
        return $this->body;
    }

    public function withBody(StreamInterface $body): MessageInterface
    {
        $this->body = $body;
        return $this;
    }
}
