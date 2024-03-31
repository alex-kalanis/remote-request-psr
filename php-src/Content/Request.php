<?php

namespace kalanis\RemoteRequestPsr\Content;


use kalanis\RemoteRequestPsr\Traits\TBody;
use kalanis\RemoteRequestPsr\Traits\THeaders;
use kalanis\RemoteRequestPsr\Traits\TProtocol;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use RuntimeException;


/**
 * Class Request
 * @package kalanis\RemoteRequestPsr\Content
 * Note: Contrary the PSR requirements this class does not offer immutability
 * That's because you need to change request properties before you set them to RemoteRequest
 */
class Request implements RequestInterface
{
    use THeaders;
    use TBody;
    use TProtocol;

    protected string $target = '';
    protected string $method = 'GET';
    protected ?UriInterface $uri = null;

    public function getRequestTarget(): string
    {
        return $this->target;
    }

    public function withRequestTarget(string $requestTarget): RequestInterface
    {
        $this->target = $requestTarget;
        return $this;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function withMethod(string $method): RequestInterface
    {
        $this->method = $method;
        return $this;
    }

    public function getUri(): UriInterface
    {
        if (is_null($this->uri)) {
            throw new RuntimeException('You must set URI first!');
        }
        return $this->uri;
    }

    public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface
    {
        $this->uri = $uri;
        $this->withHeader('Host', $this->createHostHeader($uri));
        return $this;
    }

    protected function createHostHeader(UriInterface $uri): string
    {
        $port = $uri->getPort();
        return $uri->getHost()
            . (empty($port) || (80 == $port) ? '' : ':' . $port);
    }
}
