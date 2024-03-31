<?php

namespace kalanis\RemoteRequestPsr\Content;


use InvalidArgumentException;
use Psr\Http\Message\UriInterface;


/**
 * Class Address
 * @package kalanis\RemoteRequestPsr\Content
 * Simple implementation of URI interface as address
 */
class Address implements UriInterface
{
    protected string $scheme = '';
    protected string $target = '';
    protected string $userInfo = '';
    protected string $host = '';
    protected int $port = 0;
    protected string $path = '/';
    protected string $query = '';
    protected string $fragment = '';
    protected string $user = '';
    protected ?string $pass = null;

    public function getScheme(): string
    {
        return $this->scheme;
    }

    public function getAuthority(): string
    {
        return empty($this->getHost())
            ? ''
            : (
                (!empty($this->getUserInfo()) ? $this->getUserInfo() . '@' : '')
                . $this->getHost()
                . (empty($this->getPort()) || (80 == $this->getPort()) ? '' : ':' . $this->getPort())
            )
        ;
    }

    public function getUserInfo(): string
    {
        return strval($this->user) .
            (empty($this->pass)
            ? ''
            : ':' . strval($this->pass));
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): ?int
    {
        if (!empty($this->port)) {
            return $this->port;
        }
        if (empty($this->scheme)) {
            return null;
        }
        return 80;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function getFragment(): string
    {
        return $this->fragment;
    }

    public function withScheme(string $scheme): UriInterface
    {
        $this->scheme = $scheme;
        return $this;
    }

    public function withUserInfo(string $user, ?string $password = null): UriInterface
    {
        $this->user = $user;
        $this->pass = $password;
        return $this;
    }

    public function withHost(string $host): UriInterface
    {
        $this->host = $host;
        return $this;
    }

    public function withPort(?int $port): UriInterface
    {
        if (is_null($port)) {
            $this->port = 0;
        } else {
            if (65535 < $port) {
                throw new InvalidArgumentException('Port number is too large');
            } elseif (0 > $port) {
                throw new InvalidArgumentException('Port number is too low');
            }
            $this->port = $port;
        }
        return $this;
    }

    public function withPath(string $path): UriInterface
    {
        $this->path = empty($path) ? '/' : $path;
        return $this;
    }

    public function withQuery(string $query): UriInterface
    {
        $this->query = $query;
        return $this;
    }

    public function withFragment(string $fragment): UriInterface
    {
        $this->fragment = $fragment;
        return $this;
    }

    public function __toString(): string
    {
        $sch = $this->getScheme();
        $auth = $this->getAuthority();
        $pt = $this->getPath();
        $q = $this->getQuery();
        $fr = $this->getFragment();
        return
            (empty($sch) ? '' : $sch . ':')
            . (empty($auth) ? '' : '//' . $auth)
            . ((!$this->detectRoot($pt) && !empty($auth)) ? '/' . $pt : $pt)
            . (empty($q) ? '' : '?' . $q)
            . (empty($fr) ? '' : '#' . $fr)
        ;
    }

    protected function detectRoot(string $path): bool
    {
        return isset($path[0]) && ('/' == $path[0]);
    }
}
