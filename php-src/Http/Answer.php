<?php

namespace kalanis\RemoteRequestPsr\Http;


use kalanis\RemoteRequest\Protocols;


/**
 * Class Answer
 * @package kalanis\RemoteRequestPsr\Http
 */
class Answer extends Protocols\Http\Answer
{
    /**
     * @return resource|string|null
     */
    public function getBody()
    {
        return $this->body;
    }
}
