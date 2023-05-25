<?php

namespace kalanis\RemoteRequestPsr\Traits;


use Psr\Http\Message\MessageInterface;


/**
 * Trait THeaders
 * @package kalanis\RemoteRequestPsr\Traits
 * Note: Contrary the PSR requirements this class does not offer immutability
 * That's because you need to change request properties before you set them to RemoteRequest
 */
trait THeaders
{
    /** @var array<string, array<int, string>> */
    protected $headers = [];

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader(string $name): bool
    {
        $lookup = mb_strtolower($name);
        foreach ($this->headers as $key => $values) {
            if (mb_strtolower($key) == $lookup) {
                return true;
            }
        }
        return false;
    }

    public function getHeader(string $name): array
    {
        $lookup = mb_strtolower($name);
        $wanted = [];
        foreach ($this->headers as $key => $values) {
            if (mb_strtolower($key) == $lookup) {
                $wanted = array_merge($wanted, $values);
            }
        }
        return $wanted;
    }

    public function getHeaderLine(string $name): string
    {
        return implode(',', $this->getHeader($name));
    }

    public function withHeader(string $name, $value): MessageInterface
    {
        $lookup = mb_strtolower($name);
        foreach ($this->headers as $key => $values) {
            if (mb_strtolower($key) == $lookup) {
                if (is_array($value)) {
                    $this->headers[$key] = array_merge($this->headers[$key], array_map('strval', $value));
                } else {
                    $this->headers[$key] = array_merge($this->headers[$key], [strval($value)]);
                }
                return $this;
            }
        }
        return $this->withAddedHeader($name, $value);
    }

    public function withAddedHeader(string $name, $value): MessageInterface
    {
        $this->withoutHeader($name);
        if (is_array($value)) {
            $this->headers[$name] = array_map('strval', $value);
        } else {
            $this->headers[$name] = [strval($value)];
        }
        return $this;
    }

    public function withoutHeader(string $name): MessageInterface
    {
        $lookup = mb_strtolower($name);
        $wanted = [];
        foreach ($this->headers as $key => $values) {
            if (mb_strtolower($key) == $lookup) {
                $wanted[] = $key;
            }
        }

        foreach ($wanted as $item) {
            unset($this->headers[$item]);
        }
        return $this;
    }
}
