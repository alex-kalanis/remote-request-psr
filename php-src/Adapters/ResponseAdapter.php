<?php

namespace kalanis\RemoteRequestPsr\Adapters;


use kalanis\RemoteRequest\RequestException;
use kalanis\RemoteRequestPsr\Http\Answer;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;


/**
 * Class ResponseAdapter
 * @package kalanis\RemoteRequestPsr\Adapters
 * Response adapter for PSR Interface
 * It is not possible to use that to set things, it just returns the data
 */
class ResponseAdapter implements ResponseInterface
{
    protected Answer $answer;
    protected StreamAdapter $stream;

    /**
     * @param Answer $answer
     * @throws RequestException
     */
    public function __construct(Answer $answer)
    {
        $this->answer = $answer;
        $this->stream = new StreamAdapter($answer);
    }

    public function getProtocolVersion(): string
    {
        return '1.1';
    }

    public function withProtocolVersion(string $version): MessageInterface
    {
        return $this;
    }

    public function getHeaders(): array
    {
        return $this->answer->getAllHeaders();
    }

    public function hasHeader(string $name): bool
    {
        return !empty($this->getHeader($name));
    }

    public function getHeader(string $name): array
    {
        $want = mb_strtolower($name);
        $available = [];
        foreach ($this->answer->getAllHeaders() as $key => $values) {
            if (strtolower($key) == $want) {
                $available = array_merge($available, $values);
            }
        }
        return $available;
    }

    public function getHeaderLine(string $name): string
    {
        return implode(',', $this->getHeader($name));
    }

    public function withHeader(string $name, $value): MessageInterface
    {
        return $this;
    }

    public function withAddedHeader(string $name, $value): MessageInterface
    {
        return $this;
    }

    public function withoutHeader(string $name): MessageInterface
    {
        return $this;
    }

    public function getBody(): StreamInterface
    {
        return $this->stream;
    }

    public function withBody(StreamInterface $body): MessageInterface
    {
        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->answer->getCode();
    }

    public function withStatus(int $code, string $reasonPhrase = ''): ResponseInterface
    {
        return $this;
    }

    public function getReasonPhrase(): string
    {
        return $this->answer->getReason();
    }
}
