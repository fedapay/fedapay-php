<?php

namespace Fedapay;

/**
 * Class Fedapay
 *
 * @package Fedapay
 */
class Fedapay
{
    const VERSION = '1.0.0';

    // @var string The Fedapay API key to be used for requests.
    protected static $apiKey = null;

    // @var string|null The Fedapay token to be used for oauth requests.
    protected static $token = null;

    // @var string|null The account ID for connected accounts requests.
    public static $accountId = null;

    // @var string The environment for the Fedapay API.
    protected static $environment = 'sandbox';

    // @var string The api version.
    protected static $apiVersion = 'v1';

    // @var bool verify ssl certs.
    protected static $verifySslCerts = true;

    // @var string|null the ca bundle path.
    protected static $caBundlePath = null;

    /**
     * @return string The API key used for requests.
     */
    public static function getApiKey()
    {
        return self::$apiKey;
    }

    /**
     * Sets the API key to be used for requests.
     *
     * @param string $apiKey
     * @return void
     */
    public static function setApiKey($apiKey)
    {
        self::$apiKey = $apiKey;
    }

    /**
     * @return string The token used for requests.
     */
    public static function getToken()
    {
        return self::$token;
    }

    /**
     * Sets the token to be used for requests.
     *
     * @param string $token
     * @return void
     */
    public static function setToken($token)
    {
        self::$token = $token;
    }

    /**
     * @return string The account id used for connected account.
     */
    public static function getAccountId()
    {
        return self::$accountId;
    }

    /**
     * Sets the account id to be used for connected account.
     *
     * @param string $token
     * @return void
     */
    public static function setAccountId($accountId)
    {
        self::$accountId = $accountId;
    }

    /**
     * @return string The API version used for requests.
     */
    public static function getApiVersion()
    {
        return self::$apiVersion;
    }

    /**
     * @param string $apiVersion The API version.
     * @return void
     */
    public static function setApiVersion($apiVersion)
    {
        self::$apiVersion = $apiVersion;
    }

    /**
     * @return bool Determine if the request should verify SSL certificate.
     */
    public static function getVerifySslCerts()
    {
        return self::$verifySslCerts;
    }

    /**
     * @param bool $verify The verify ssl certificates value.
     * @return void
     */
    public static function setVerifySslCerts($verify)
    {
        self::$verifySslCerts = $verify;
    }

    /**
     * @return string Return the path of the certificate.
     */
    public static function getCaBundlePath()
    {
        if (!self::$caBundlePath) {
            self::$caBundlePath = dirname(__FILE__) . '/../data/ca-certificates.crt';
        }

        return self::$caBundlePath;
    }

    /**
     * @param string $path The path of the certificate.
     * @return void
     */
    public static function setCaBundlePath($path)
    {
        self::$caBundlePath = $path;
    }

    /**
     * @return string | null The Fedapay environment
     */
    public static function getEnvironment()
    {
        return self::$environment;
    }

    /**
     * @param string $environment The API environment.
     * @return void
     */
    public static function setEnvironment($environment)
    {
        self::$environment = $environment;
    }
}
