<?php

namespace FedaPay;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;

/**
* Class Requestor
*
* @package FedaPay
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
    * Http Client
    * @var GuzzleHttp\ClientInterface
    */
    protected static $httpClient;

    public function __construct()
    {
        $this->apiKey = FedaPay::getApiKey();
        $this->token = FedaPay::getToken();
        $this->environment = FedaPay::getEnvironment();
        $this->apiVersion = FedaPay::getApiVersion();
        $this->accountId = FedaPay::getAccountId();
    }

    /**
    * @param GuzzleHttp\ClientInterface $client The requestor http client.
    * @return void
    */
    public static function setHttpClient($client)
    {
        self::$httpClient = $client;
    }

    /**
     * The http client
     * @return GuzzleHttp\ClientInterface
     */
    private function httpClient()
    {
        if (!self::$httpClient) {
            $options = [];

            if (FedaPay::getVerifySslCerts()) {
                $options['verify'] = FedaPay::getCaBundlePath();
            }

            self::$httpClient = new \GuzzleHttp\Client($options);
        }

        return self::$httpClient;
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
            $response = $this->httpClient()->request($method, $url, $options);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            $this->handleRequestException($e);
        }
    }

    /**
     * Format http request error
     * @param GuzzleHttp\Exception\RequestException $e
     * @throws Error\ApiConnection
     * @return void
     */
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

    /**
     * Return the default request headers
     * @return array
     */
    protected function defaultHeaders()
    {
        $default = [
            'X-Version' => FedaPay::VERSION,
            'X-Source' => 'FedaPay PhpLib',
            'Authorization' => 'Bearer '. ($this->apiKey ?: $this->token)
        ];

        if ($this->accountId) {
            $default['FedaPay-Account'] = $this->accountId;
        }

        return $default;
    }

    /**
     * Return the base url of the requests
     * @return string
     */
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

    /**
     * Return the request url
     * @return string
     */
    protected function url($path)
    {
        return $this->baseUrl() . '/' . $this->apiVersion . $path;
    }
}
