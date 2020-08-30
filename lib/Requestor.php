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

    const DEVELOPMENT_BASE = 'https://dev-api.fedapay.com';

    /**
    * Http Client
    * @var GuzzleHttp\ClientInterface
    */
    protected static $httpClient;

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
    public function request($method, $path, $params = null, $headers = null)
    {
        $params = $params ?: [];
        $headers = $headers ?: [];

        $params = array_merge($this->defaultParams(), $params);
        $headers = array_merge($this->defaultHeaders(), $headers);
        $url = $this->url($path);
        $rawHeaders = [];

        foreach ($headers as $h => $v) {
            $rawHeaders[] = $h . ': ' . $v;
        }

        list($rbody, $rcode, $rheaders) = $this->httpClient()->request($method, $url, $params, $rawHeaders);

        $json = $this->_interpretResponse($rbody, $rcode, $rheaders);

        return $json;
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
     * Return the default request params
     * @return array
     */
    protected function defaultParams()
    {
        $params = [];

        if (FedaPay::getLocale()) {
            $params['locale'] = FedaPay::getLocale();
        }

        return $params;
    }

    /**
     * Return the default request headers
     * @return array
     */
    protected function defaultHeaders()
    {
        $auth = FedaPay::getApiKey() ?: FedaPay::getToken();
        $apiVersion = FedaPay::getApiVersion();
        $accountId = FedaPay::getAccountId();

        $default = [
            'X-Version' => FedaPay::VERSION,
            'X-Api-Version' => $apiVersion,
            'X-Source' => 'FedaPay PhpLib',
            'Authorization' => "Bearer $auth"
        ];

        if ($accountId) {
            $default['FedaPay-Account'] = $accountId;
        }

        return $default;
    }

    /**
     * Return the base url of the requests
     * @return string
     */
    protected function baseUrl()
    {
        $apiBase = FedaPay::getApiBase();
        $environment = FedaPay::getEnvironment();

        if ($apiBase) {
            return $apiBase;
        }

        switch ($environment) {
            case 'development':
            case 'dev':
                return self::DEVELOPMENT_BASE;
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
        return $this->baseUrl() . '/' . FedaPay::getApiVersion() . $path;
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
        $resp = json_decode($rbody, true);
        $jsonError = json_last_error();

        if ($resp === null && $jsonError !== JSON_ERROR_NONE) {
            $msg = "Invalid response body from API: $rbody "
              . "(HTTP response code was $rcode, json_last_error() was $jsonError)";
            throw new Error\ApiConnection($msg, $rcode, $rbody);
        }

        if ($rcode < 200 || $rcode >= 300) {
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
        $msg = isset($resp['message']) ? $resp['message'] : 'ApiConnection Error' ;
        throw new Error\ApiConnection($msg, $rcode, $rbody, $resp, $rheaders);
    }
}
