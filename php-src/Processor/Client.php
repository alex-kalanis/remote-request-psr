<?php

namespace kalanis\RemoteRequestPsr\Processor;


use kalanis\RemoteRequest\RequestException;
use kalanis\RemoteRequestPsr\Exceptions\ClientException;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;


/**
 * Class Client
 * @package kalanis\RemoteRequestPsr\Processor
 * Process requests in format of PSR interfaces
 */
class Client extends Simple implements ClientInterface
{
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        try {
            return $this->process($request);
        } catch (RequestException $ex) {
            throw new ClientException($ex->getMessage(), $ex->getCode(), $ex);
        }
    }
}
