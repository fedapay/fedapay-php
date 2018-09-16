<?php

namespace FedaPay\Error;

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
     * @var string
     */
    private $httpBody;

    /**
     * @var json
     */
    private $jsonBody;

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
        $httpBody = null,
        $jsonBody = null,
        $httpHeaders = null
    ) {
        parent::__construct($message);

        $this->httpStatus = $httpStatus;
        $this->httpBody = $httpBody;
        $this->jsonBody = $jsonBody;
        $this->httpHeaders = $httpHeaders;

        $this->errorMessage = isset($jsonBody["message"]) ? $jsonBody["message"]: null;
        $this->errors = isset($jsonBody["errors"]) ? $jsonBody["errors"]: null;
    }

    public function getHttpStatus()
    {
        return $this->httpStatus;
    }

    public function getHttpBody()
    {
        return $this->httpBody;
    }

    public function getJsonBody()
    {
        return $this->jsonBody;
    }

    public function getHttpHeaders()
    {
        return $this->httpHeaders;
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
}
