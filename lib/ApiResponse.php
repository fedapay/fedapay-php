<?php
namespace Fedapay;
/**
 * Class ApiResponse
 *
 * @package Fedapay
 */
class ApiResponse
{
    public $headers;
    public $body;
    public $status;
    /**
     * @param string $body
     * @param integer $status
     * @param array|null $headers
     *
     * @return obj An APIResponse
     */
    public function __construct($body, $status, $headers)
    {
        $this->body = $body;
        $this->status = $status;
        $this->headers = $headers;      
    }
}
