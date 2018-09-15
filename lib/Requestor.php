<?php

namespace FedaPay;

/**
* Class Requestor
*
* @package FedaPay
*/
class Requestor
{
    const SANDBOX_BASE = 'https://sandbox-api.fedapay.com';

    const PRODUCTION_BASE = 'https://api.fedapay.com';

    /**
    * Api key
    * @var string
    */
    protected $apiKey;

    /**
    * Api base
    * @var string
    */
    protected $apiBase;

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
        $this->apiBase = FedaPay::getApiBase();
        $this->token = FedaPay::getToken();
        $this->environment = FedaPay::getEnvironment();
        $this->apiVersion = FedaPay::getApiVersion();
        $this->accountId = FedaPay::getAccountId();
    }

    /**
     * @static
     *
     * @param HttpClient\ClientInterface $client
     */
    public static function setHttpClient($client)
    {
        self::$httpClient = $client;
    }

    /**
     * @return HttpClient\ClientInterface
     */
    private function httpClient()
    {
           if (!self::$httpClient) {
            $options = [];

            if (FedaPay::getVerifySslCerts()) {
                $options['verify'] = FedaPay::getCaBundlePath();
            }

            self::$httpClient = HttpClient\CurlClient::instance();
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
    public function request($method, $path, $headers = null, $params = null)
    {
            $params = $params ?: [];
            $headers = $headers ?: [];

            $headers = array_merge($headers, $this->defaultHeaders());
            $url = $this->url($path);
            $method = strtolower($method);

            list($rbody, $rcode, $rheaders)   = $this->httpClient()->request($method, $url, $headers, $params);
            $json = $this->_interpretResponse($rbody, $rcode, $rheaders);
            $resp = new Response($rbody, $rcode, $rheaders, $json);

            return $resp->json;
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
            'Authorization: Bearer '. ($this->apiKey ?: $this->token)
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
        if ($this->apiBase) {
            return $this->apiBase;
        }

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

    /**
     * @param string $rbody
     * @param int    $rcode
     * @param array  $rheaders
     *
     * @return mixed
     */
    private function _interpretResponse($rbody, $rcode, $rheaders)
    {
        try {
            $resp = json_decode($rbody, true);
        } catch (Exception $e) {
            $msg = "Invalid response body from API: $rbody "
              . "(HTTP response code was $rcode)";
            throw new Error\ApiConnection($msg, $rcode, $rbody);
        }
        if ($rcode != null && $rcode != '200') {
            $this->handleErrorResponse($rbody, $rcode, $rheaders, $resp);
        }

        return $resp;
    }

    /**
     * @param string $rbody A JSON string.
     * @param int $rcode
     * @param array $rheaders
     * @param array $resp
     *
     * @throws Error\InvalidRequest if the error is caused by the user.
     */
    public function handleErrorResponse($rbody, $rcode, $rheaders, $resp)
    {
        $msg = isset($resp['message']) ? $resp['message'] : $resp['errors'] ;
        throw new Error\ApiConnection($msg, $rcode, $rbody, $resp, $rheaders);
    }
}
