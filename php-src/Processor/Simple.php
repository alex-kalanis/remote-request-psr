<?php

namespace kalanis\RemoteRequestPsr\Processor;


use kalanis\RemoteRequest\Connection;
use kalanis\RemoteRequest\Interfaces;
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

        $libHttpAnswer = new Answer();
        return new ResponseAdapter($libHttpAnswer->setResponse($this->runner($libParams, $libQuery)));
    }

    /**
     * @param Connection\Params\AParams $params
     * @param Interfaces\IQuery $query
     * @return resource|null
     * @codeCoverageIgnore because external resources
     */
    protected function runner(Connection\Params\AParams $params, Interfaces\IQuery $query)
    {
        $libProcessor = new Connection\Processor(); # tcp/ip http/ssl
        $libProcessor->setConnectionParams($params);
        $libProcessor->setData($query);
        return $libProcessor->getResponse();
    }
}
