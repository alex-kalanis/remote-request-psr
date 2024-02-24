<?php

namespace BasicTests;


use CommonTestClass;
use kalanis\RemoteRequest\RequestException;
use kalanis\RemoteRequestPsr\Content\Request;
use kalanis\RemoteRequestPsr\Content\Response;
use kalanis\RemoteRequestPsr\Content\Stream;
use kalanis\RemoteRequestPsr\Processor\Client;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;


class ClientProcessorTest extends CommonTestClass
{
    /**
     * @throws RequestException
     * @throws ClientExceptionInterface
     */
    public function testPass(): void
    {
        $lib = new XClient();
        $query = new Request();
        $query->withBody(new Stream('testing_data'));
        $this->assertEquals('testing_data', strval($lib->sendRequest($query)->getBody()));
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function testFail(): void
    {
        $lib = new XClientFail();
        $this->expectException(ClientExceptionInterface::class);
        $lib->sendRequest(new Request());
    }
}


class XClient extends Client
{
    public function process(RequestInterface $request): ResponseInterface
    {
        $response = new Response();
        $response->withBody($request->getBody());
        return $response;
    }
}


class XClientFail extends Client
{
    public function process(RequestInterface $request): ResponseInterface
    {
        throw new RequestException('mock');
    }
}
