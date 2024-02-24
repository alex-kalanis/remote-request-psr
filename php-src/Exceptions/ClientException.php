<?php

namespace kalanis\RemoteRequestPsr\Exceptions;


use kalanis\RemoteRequest\RequestException;
use Psr\Http\Client\ClientExceptionInterface;


/**
 * Class ClientException
 * @package kalanis\RemoteRequestPsr\Processor
 * Process errors in format of PSR interfaces
 */
class ClientException extends RequestException implements ClientExceptionInterface
{
}
