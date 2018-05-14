<?php

namespace FedaPay\Error;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Exception;

/**
 * Class Base
 *
 * @package FedaPay\Error
 */
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

    /**
     * @var string
     */
    private $errorMessage;

    /**
     * @var Array
     */
    private $errors;

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

        $this->fetchErrors();
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

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function hasErrors()
    {
        return !empty($this->errors);
    }

    private function fetchErrors()
    {
        if ($this->httpResponse) {
            $body = $this->httpResponse->getBody()->getContents();
            $json = json_decode($body, true);

            if ($json && isset($json['message'])) {
                $this->errorMessage = $json['message'];
            }

            if ($json && isset($json['errors'])) {
                $this->errors = $json['errors'];
            }
        }
    }
}
