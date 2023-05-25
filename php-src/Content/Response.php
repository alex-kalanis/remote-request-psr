<?php

namespace kalanis\RemoteRequestPsr\Content;


use kalanis\RemoteRequestPsr\Traits\TBody;
use kalanis\RemoteRequestPsr\Traits\THeaders;
use kalanis\RemoteRequestPsr\Traits\TProtocol;
use Psr\Http\Message\ResponseInterface;


/**
 * Class Response
 * @package kalanis\RemoteRequestPsr\Content
 * Note: Contrary the PSR requirements this class does not offer immutability
 * That's because you need to change request properties before you set them to RemoteRequest
 */
class Response implements ResponseInterface
{
    use THeaders;
    use TBody;
    use TProtocol;

    /** @var int */
    protected $status = 0;
    /** @var string */
    protected $reason = 'KO';

    public function getStatusCode(): int
    {
        return $this->status;
    }

    public function withStatus(int $code, string $reasonPhrase = ''): ResponseInterface
    {
        $this->status = $code;
        $this->reason = $reasonPhrase;
        return $this;
    }

    public function getReasonPhrase(): string
    {
        return $this->reason;
    }
}
