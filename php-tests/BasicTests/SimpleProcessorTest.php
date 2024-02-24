<?php

namespace BasicTests;


use CommonTestClass;
use kalanis\RemoteRequest\Connection;
use kalanis\RemoteRequest\Interfaces;
use kalanis\RemoteRequest\Protocols\Http;
use kalanis\RemoteRequest\RequestException;
use kalanis\RemoteRequestPsr\Content\Address;
use kalanis\RemoteRequestPsr\Content\Request;
use kalanis\RemoteRequestPsr\Content\Stream;
use kalanis\RemoteRequestPsr\Processor\Simple;
use Psr\Http\Message\RequestInterface;


class SimpleProcessorTest extends CommonTestClass
{
    /**
     * @throws RequestException
     */
    public function testSimplePass(): void
    {
        $lib = new XSimple();
        $response = $lib->process($this->getRequest('localhost', '/somewhere', 'this_is_body'));
        $this->assertEquals(999, $response->getStatusCode());
    }

    /**
     * @throws RequestException
     */
    public function testVariantPass(): void
    {
        $url = new Address();
        $url->withHost('localhost')
            ->withPath('/somewhere')
            ->withScheme('https')
            ->withUserInfo('foo', 'bar');
        $request = new Request();
        $request->withUri($url);
        $request->withBody(new Stream(''));

        $lib = new XSimple();
        $response = $lib->process($request);
        $this->assertEquals(999, $response->getStatusCode());
    }

    /**
     * @throws RequestException
     */
    public function testFail(): void
    {
        $lib = new XSimpleFail();
        $this->expectException(RequestException::class);
        $lib->process($this->getRequest());
    }

    /**
     * @param string $host
     * @param string $path
     * @param string $body
     * @throws RequestException
     * @return RequestInterface
     */
    protected function getRequest(string $host = '', string $path = '', string $body = ''): RequestInterface
    {
        $url = new Address();
        $url->withHost($host)->withPath($path);
        $request = new Request();
        $request->withUri($url);
        $request->withBody(new Stream($body));
        return $request;
    }
}


class XSimple extends Simple
{
    protected function runner(Connection\Params\AParams $params, Interfaces\IQuery $query)
    {
        $f = fopen('php://memory', 'r+');
        fwrite($f, $this->postToServer(
            $params->getSchema() . '---' . $params->getHost() . '---' . $params->getPort() . '---' . $params->getTimeout()
            , stream_get_contents($query->getData(), -1, 0)));
        rewind($f);
        return $f;
    }

    protected function postToServer(string $address, $contextData): string
    {
        return 'HTTP/0.1 999 PASS' . Http::DELIMITER
            . Http::DELIMITER
            . $address . Http::DELIMITER
            . Http::DELIMITER
            . serialize($contextData);
    }
}


class XSimpleFail extends Simple
{
    /**
     * @param Connection\Params\AParams $params
     * @param Interfaces\IQuery $query
     * @throws RequestException
     * @return resource|void|null
     */
    protected function runner(Connection\Params\AParams $params, Interfaces\IQuery $query)
    {
        throw new RequestException('mock');
    }
}
