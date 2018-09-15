<?php

namespace FedaPay;

/**
 * Class Response
 *
 * @package FedaPay
 */
class Response
{
    public $headers;
    public $body;
    public $json;
    public $code;

    /**
     * @param string $body
     * @param integer $code
     * @param array|null $headers
     * @param array|null $json
     *
     * @return obj An Response
     */
    public function __construct($body, $code, $headers, $json)
    {
        $this->body = $body;
        $this->code = $code;
        $this->headers = $headers;
        $this->json = $json;
    }
}