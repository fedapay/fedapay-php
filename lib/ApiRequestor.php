<?php

namespace Fedapay;

/**
 * Class ApiRequestor
 *
 * @package Fedapay
 */
class ApiRequestor
{
    private $_apiKey;

    private $_apiBase;

    private static $_httpClient;

    public function __construct($apiKey = null, $apiBase = null)
    {
        $this->_apiKey = $apiKey;
        if (!$apiBase) {
            $apiBase = Fedapay::$apiBase;
        }
        $this->_apiBase = $apiBase;
    }

    /**
     * @param string $method
     * @param string $url
     * @param array|null $params
     * @param array|null $headers
     *
     * @return array An array whose first element is an API response and second
     *    element is the API key used to make the request.
     */
    public function request($method, $url, $params = null, $headers = null)
    {
        if (!$params) {
            $params = array();
        }
        if (!$headers) {
            $headers = array();
        }
        list($rbody, $rcode, $rheaders, $myApiKey) =
        $this->_requestRaw($method, $url, $params, $headers);
        $json = $this->_interpretResponse($rbody, $rcode, $rheaders);
        $resp = new ApiResponse($rbody, $rcode, $rheaders, $json);
        return array($resp, $myApiKey);
    }

    /**
     * @param string $rbody A JSON string.
     * @param int $rcode
     * @param array $rheaders
     * @param array $resp
     *
     * @throws Error\InvalidRequest if the error is caused by the user.
     * @throws Error\Api otherwise.
     */
    public function handleErrorResponse($rbody, $rcode, $rheaders, $resp)
    {
        if (!is_array($resp) || !isset($resp['error'])) {
            $msg = "Invalid response object from API: $rbody "
              . "(HTTP response code was $rcode)";
            throw new Error\Api($msg, $rcode, $rbody, $resp, $rheaders);
        }

        $errorData = $resp['error'];

        // $error = null;
        // if (is_string($errorData)) {
        //     $error = self::_specificOAuthError($rbody, $rcode, $rheaders, $resp, $errorData);
        // }
        // if (!$error) {
        //     $error = self::_specificAPIError($rbody, $rcode, $rheaders, $resp, $errorData);
        // }

        throw $error;
    }


    private static function _defaultHeaders($apiKey, $clientInfo = null)
    {
        $uaString = 'Fedapay/v1 PhpBindings/' . Fedapay::VERSION;

        $langVersion = phpversion();
        $uname = php_uname();

        $appInfo = Fedapay::getAppInfo();
        $ua = array(
            'bindings_version' => Fedapay::VERSION,
            'lang' => 'php',
            'lang_version' => $langVersion,
            'publisher' => 'fedapay',
            'uname' => $uname,
        );
        if ($clientInfo) {
            $ua = array_merge($clientInfo, $ua);
        }
        if ($appInfo !== null) {
            $uaString .= ' ' . self::_formatAppInfo($appInfo);
            $ua['application'] = $appInfo;
        }

        $defaultHeaders = array(
            'X-Fedapay-Client-User-Agent' => json_encode($ua),
            'User-Agent' => $uaString,
            'Authorization' => 'Bearer ' . $apiKey,
        );
        return $defaultHeaders;
    }

  
    private function _interpretResponse($rbody, $rcode, $rheaders)
    {
        $resp = json_decode($rbody, true);
        $jsonError = json_last_error();
        if ($resp === null && $jsonError !== JSON_ERROR_NONE) {
            $msg = "Invalid response body from API: $rbody "
              . "(HTTP response code was $rcode, json_last_error() was $jsonError)";
            throw new Error\Api($msg, $rcode, $rbody);
        }

        if ($rcode < 200 || $rcode >= 300) {
            $this->handleErrorResponse($rbody, $rcode, $rheaders, $resp);
        }
        return $resp;
    }

    public static function setHttpClient($client)
    {
        self::$_httpClient = $client;
    }

    private function httpClient()
    {
        if (!self::$_httpClient) {
            self::$_httpClient = HttpClient\CurlClient::instance();
        }
        return self::$_httpClient;
    }
}
