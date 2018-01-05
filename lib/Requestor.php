<?php

namespace Fedapay;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;

/**
 * Class Requestor
 *
 * @package Fedapay
 */
class Requestor
{
    const SANDBOX_BASE = 'https://api.sandbox.fedapay.com';

    const PRODUCTION_BASE = 'https://api.production.fedapay.com';

    protected $_apiKey;

    protected $_environment;

    protected $client;

    public function __construct($apiKey = null, $apiBase = null)
    {
        $this->_apiKey = $apiKey ?: Fedapay::$apiKey;
        $this->_apiBase = $apiBase ?: Fedapay::$apiBase;

        $this->client = new \GuzzleHttp\Client();
    }

    /**
     * @param string     $method
     * @param string     $url
     * @param array|null $params
     * @param array|null $headers
     *
     * @return array An API response.
     */
    public function requestor($method, $path, $params, $data = [])
    {
        try {
            $header = $this->_defaultHeaders();
            $url = $this->url($path);
            $method = strtoupper($method);
            $options = [ 'query' => $params, 'headers' => $header ];

            if ($method !== 'GET') {
                $options['json'] = $data;
            }

            return json_decode($response->getBody()->getContents());
        } catch (RequestException $e) {
            $response = $this->StatusCodeHandling($e);

            return $response;
        }
    }

    protected function statusCodeHandling($e)
    {
        $response = [
            'statuscode' => $e->getResponse()->getStatusCode(),
            'error' => json_decode($e->getResponse()->getBody(true)->getContents())
        ];

        return $response;
    }

    protected function defaultHeaders()
    {
        return [
            'X-Version' => '1.0.0',
            'X-Source' => 'PhpLib',
            'Authorization' => 'Bearer '. $this->_apiKey,
        ];
    }

    protected function baseUrl()
    {
        switch ($this->_environment) {
        case 'development':
        case 'sandbox':
        case 'test':
            return SANDBOX_BASE;
        case 'production':
        case 'live':
            return PRODUCTION_BASE;
        }
    }

    protected function url($path)
    {
        return $this->baseUrl() . '/' . $path;
    }
}
