<?php

namespace kalanis\RemoteRequestPsr\Http;


use kalanis\RemoteRequest\Protocols\Http\Query\TAuthBasic;


/**
 * Class BasicAuth
 * @package kalanis\RemoteRequestPsr\Http
 * Message to the remote server compilation - protocol http, uses StreamInterface as body, use basic authentication
 */
class BasicAuth extends Query
{
    use TAuthBasic;

    public function getData()
    {
        $this->authHeader();
        return parent::getData();
    }
}
