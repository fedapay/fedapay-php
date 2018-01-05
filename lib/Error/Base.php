<?php

namespace Fedapay\Error;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

use Exception;

class Base extends Exception
{
    /**
     * @var int
     */
    private $httpStatus;

    /**
     * @var RequestInterface
     */
    private $httpRequest;

    /**
     * @var ResponseInterface
     */
    private $httpResponse;

    public function __construct(
        $message,
        $httpStatus = null,
        $httpRequest = null,
        $httpResponse = null
    ) {
        parent::__construct($message);

        $this->httpStatus = $httpStatus;
        $this->httpRequest = $httpRequest;
        $this->httpResponse = $httpResponse;
    }

    public function getHttpStatus()
    {
        return $this->httpStatus;
    }

    public function getHttpRequest()
    {
        return $this->httpRequest;
    }

    public function getHttpResponse()
    {
        return $this->httpResponse;
    }
}
