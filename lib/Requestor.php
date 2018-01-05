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

    /**
     * Api key
     * @var string
     */
    protected $apiKey;

    /**
     * Api environment
     * @var string
     */
    protected $environment;

    /**
     * Api version
     * @var string
     */
    protected $apiVersion;

    /**
     * HttpClient
     * @var GuzzleHttp\Client
     */
    protected $client;

    public function __construct($apiKey = null, $environment = null, $apiVersion = null)
    {
        $this->apiKey = $apiKey ?: Fedapay::getApiKey();
        $this->environment = $environment ?: Fedapay::getEnvironment();
        $this->apiVersion = $apiVersion ?: Fedapay::getApiVersion();

        $this->client = new \GuzzleHttp\Client();
    }

    /**
     * @return string The API key used for requests.
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Sets the API key to be used for requests.
     *
     * @param string $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @param string     $method
     * @param string     $url
     * @param array|null $params
     * @param array|null $headers
     *
     * @return array An API response.
     */
    public function request($method, $path, $params = [], $headers = [])
    {
        try {
            $header = $this->_defaultHeaders();
            $url = $this->url($path);
            $method = strtoupper($method);
            $options = [ 'query' => $params, 'headers' => $header ];

            switch ($method) {
                case 'GET':
                case 'HEAD':
                case 'DELETE':
                    $options['query'] = $params;
                    break;
                default:
                    $options['json'] = $params;
                    break;
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
            'Authorization' => 'Bearer '. $this->apiKey,
        ];
    }

    protected function baseUrl()
    {
        switch ($this->environment) {
        case 'development':
        case 'sandbox':
        case 'test':
        case null:
            return SANDBOX_BASE;
        case 'production':
        case 'live':
            return PRODUCTION_BASE;
        }
    }

    protected function url($path)
    {
        return $this->baseUrl() . '/' . $this->apiVersion . '/' . $path;
    }
}
