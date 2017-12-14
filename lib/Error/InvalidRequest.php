<?php
namespace Fedapay\Error;
class InvalidRequest extends Base
{
    public function __construct(
        $message,
        $fedapayParam,
        $httpStatus = null,
        $httpBody = null,
        $httpHeaders = null
    ) {
        parent::__construct($message, $httpStatus, $httpBody, $httpHeaders);
        $this->fedapayParam = $fedapayParam;
    }
    public function getFedapayParam()
    {
        return $this->fedapayParam;
    }
}
