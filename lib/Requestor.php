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
    const SANDBOX_BASE = 'https://sdx-api.fedapay.com';

    const PRODUCTION_BASE = 'https://api.fedapay.com';

    /**
    * Api key
    * @var string
    */
    protected $apiKey;

    /**
    * Token
    * @var string
    */
    protected $token;

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
    * Account id
    * @var string
    */
    protected $accountId;

    /**
    * HttpClient
    * @var GuzzleHttp\Client
    */
    protected $client;

    public function __construct()
    {
        $this->apiKey = Fedapay::getApiKey();
        $this->token = Fedapay::getToken();
        $this->environment = Fedapay::getEnvironment();
        $this->apiVersion = Fedapay::getApiVersion();
        $this->accountId = Fedapay::getAccountId();

        $this->client = $this->defaultClient();
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

    private function defaultClient()
    {
        $options = [];

        if (Fedapay::getVerifySslCerts()) {
            $options['verify'] = Fedapay::getCaBundlePath();
        }

        return new \GuzzleHttp\Client($options);
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
            $message,
            $httpStatusCode,
            $httpRequest,
            $httpResponse
        );
    }

    protected function defaultHeaders()
    {
        $default = [
            'X-Version' => '1.0.0',
            'X-Source' => 'PhpLib',
            'Authorization' => 'Bearer '. ($this->apiKey ?: $this->token)
        ];

        if ($this->accountId) {
            $default['Fedapay-Account'] = $this->accountId;
        }

        return $default;
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
