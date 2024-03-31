<?php

namespace kalanis\RemoteRequestPsr\Traits;


use Psr\Http\Message\MessageInterface;


/**
 * Trait TProtocol
 * @package kalanis\RemoteRequestPsr\Traits
 * Note: Contrary the PSR requirements this class does not offer immutability
 * That's because you need to change request properties before you set them to RemoteRequest
 */
trait TProtocol
{
    protected string $protocol = '1.0';

    public function getProtocolVersion(): string
    {
        return $this->protocol;
    }

    public function withProtocolVersion(string $version): MessageInterface
    {
        if (preg_match('#(\d+)\.(\d+)#', $version)) {
            $this->protocol = $version;
        }
        return $this;
    }
}
