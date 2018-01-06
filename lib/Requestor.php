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
    const SANDBOX_BASE = 'https://api.fedapay.com';

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

    /**
     * @param string $apiKey The api key.
     * @param string $environment the environment. Default is sandbox
     * Should be one ont these development, sandbox, test, production, live
     * @param string $apiVersion the api version. Default is v1
     */
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
     * @return string The requestor API version used for requests.
     */
    public function getApiVersion()
    {
        return $this->apiVersion;
    }

    /**
     * @param string $environment The requestor api environment.
     * @return void
     */
    public function setApiVersion($apiVersion)
    {
        $this->apiVersion = $apiVersion;
    }

    /**
     * @return string The requestor Api environment
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @param string $environment The requestor API environment.
     * @return void
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
    }

    /**
     * @return GuzzleHttp\Client The requestor client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param GuzzleHttp\Client $client The requestor client.
     * @return void
     */
    public function setClient($client)
    {
        $this->client = $client;
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
            if (is_null($headers)) {
                $headers = [];
            }

            if (is_null($params)) {
                $params = [];
            }

            $headers = array_merge($headers, $this->defaultHeaders());
            $url = $this->url($path);
            $method = strtoupper($method);
            $options = [ 'headers' => $headers ];

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

            $response = $this->client->request($method, $url, $options);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            $this->handleRequestException($e);
        }
    }

    protected function handleRequestException($e)
    {
        $message = 'Request error: '. $e->getMessage();
        $httpStatusCode = $e->hasResponse() ? $e->getResponse()->getStatusCode() : null;
        $httpRequest = $e->getRequest();
        $httpResponse = $e->getResponse();

        throw new Error\ApiConnection(
            $message, $httpStatusCode,
            $httpRequest, $httpResponse
        );
    }

    protected function defaultHeaders()
    {
        return [
            'X-Version' => '1.0.0',
            'X-Source' => 'PhpLib',
            'Authorization' => 'Bearer '. $this->apiKey
        ];
    }

    protected function baseUrl()
    {
        switch ($this->environment) {
        case 'development':
        case 'sandbox':
        case 'test':
        case null:
            return self::SANDBOX_BASE;
        case 'production':
        case 'live':
            return self::PRODUCTION_BASE;
        }
    }

    protected function url($path)
    {
        return $this->baseUrl() . '/' . $this->apiVersion . $path;
    }
}
