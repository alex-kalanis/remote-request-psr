<?php

namespace kalanis\RemoteRequestPsr\Processor;


use kalanis\RemoteRequest\Connection;
use kalanis\RemoteRequest\RequestException;
use kalanis\RemoteRequestPsr\Http\Answer;
use kalanis\RemoteRequestPsr\Http\BasicAuth;
use kalanis\RemoteRequestPsr\Http\Query;
use kalanis\RemoteRequestPsr\Adapters\ResponseAdapter;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;


/**
 * Class Simple
 * @package kalanis\RemoteRequestPsr\Processor
 * Process requests in format of PSR interfaces
 * @codeCoverageIgnore runs external queries
 */
class Simple
{
    /**
     * @param RequestInterface $request
     * @throws RequestException
     * @return ResponseInterface
     */
    public function process(RequestInterface $request): ResponseInterface
    {
        $libParams = in_array($request->getUri()->getScheme(), ['https', 'ssl'])
            ? new Connection\Params\Ssl()
            : new Connection\Params\Tcp()
        ;
        $libParams->setTarget($request->getUri()->getHost(), $request->getUri()->getPort());

        $libQuery = !empty($request->getUri()->getUserInfo()) ? new BasicAuth() : new Query();
        $libQuery
            ->setMethod($request->getMethod())
            ->setRequestSettings($libParams)
            ->setPath($request->getRequestTarget())
        ;
        if ($libQuery instanceof BasicAuth) {
            $data = explode(':', $request->getUri()->getUserInfo());
            $libQuery->setCredentials($data[0], $data[1] ?: '');
        }

        foreach ($request->getHeaders() as $name => $values) {
            $libQuery->addHeader($name, implode(',', $values));
        }
        $libQuery->setContentStream($request->getBody());

        $libProcessor = new Connection\Processor(); # tcp/ip http/ssl
        $libProcessor->setConnectionParams($libParams);
        $libProcessor->setData($libQuery);

        $libHttpAnswer = new Answer();
        return new ResponseAdapter($libHttpAnswer->setResponse($libProcessor->getResponse()));
    }
}
