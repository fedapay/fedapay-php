<?php

namespace Fedapay;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;

/**
 * Class ApiRequestor
 *
 * @package Fedapay
 */
class FedapayClient
{
    private $_apiKey;

    private $_apiBase;

    protected $client;

    public function __construct($apiKey = null, $apiBase = null)
    {
        $this->_apiKey = $apiKey;
        if (!$apiBase) {
            $apiBase = Fedapay::$apiBase;
        }
        $this->_apiBase = $apiBase;
        $this->client = new \GuzzleHttp\Client();
    }

    /**
     * @param string $method
     * @param string $url
     * @param array|null $params
     * @param array|null $headers
     *
     * @return array An API response.
     */
    public function requestor($method, $url, $params, $data=[])
    {
        try{
            $header = $this->_defaultHeaders();
            if ($method == 'get') {
              $response = $this->client->request($method,$url, array('query' => $params,'headers' => $header));
            } else {
              $response = $this->client->request($method,$url, array('query' => $params,'headers' => $header, 'json' => $data));
            }
            return json_decode($response->getBody()->getContents());
        } catch (RequestException $e) {
            $response = $this->StatusCodeHandling($e);
            return $response;
        }
    }

    protected function statusCodeHandling($e)
    {
        $response = array("statuscode" => $e->getResponse()->getStatusCode(),
        "error" => json_decode($e->getResponse()->getBody(true)->getContents()));
        return $response;
    }

    private static function _defaultHeaders()
    {
        $defaultHeaders = array(
            'X-Version' => '1.0.0',
            'X-Source' => 'PhpLib',
        );
        return $defaultHeaders;
    }

}
