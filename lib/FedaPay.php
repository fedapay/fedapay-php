<?php

namespace FedaPay;

/**
 * Class FedaPay
 *
 * @package FedaPay
 */
class FedaPay
{
    const VERSION = '0.1.7';

    // @var string The FedaPay API key to be used for requests.
    protected static $apiKey = null;

    // @var string The FedaPay API base to be used for requests.
    protected static $apiBase = null;

    // @var string|null The FedaPay token to be used for oauth requests.
    protected static $token = null;

    // @var string|null The account ID for connected accounts requests.
    public static $accountId = null;

    // @var string The environment for the FedaPay API.
    protected static $environment = 'sandbox';

    // @var string The environment for the FedaPay API.
    protected static $locale = null;

    // @var string The api version.
    protected static $apiVersion = 'v1';

    // @var bool verify ssl certs.
    protected static $verifySslCerts = true;

    // @var string|null the ca bundle path.
    protected static $caBundlePath = null;

    // @var int Maximum number of request retries
    public static $maxNetworkRetries = 0;

    // @var float Maximum delay between retries, in seconds
    private static $maxNetworkRetryDelay = 2.0;

    // @var float Initial delay between retries, in seconds
    private static $initialNetworkRetryDelay = 0.5;

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
        self::$token = null;
    }

    /**
     * @return string The API base used for requests.
     */
    public static function getApiBase()
    {
        return self::$apiBase;
    }

    /**
     * Sets the API base to be used for requests.
     *
     * @param string $apiBase
     * @return void
     */
    public static function setApiBase($apiBase)
    {
        self::$apiBase = $apiBase;
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
        self::$apiKey = null;
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
     * @return string | null The FedaPay environment
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

    /**
     * @return string | null The FedaPay locale
     */
    public static function getLocale()
    {
        return self::$locale;
    }

    /**
     * @param string $locale The API locale.
     * @return void
     */
    public static function setLocale($locale)
    {
        self::$locale = $locale;
    }

    /**
     * @return int Maximum number of request retries
     */
    public static function getMaxNetworkRetries()
    {
        return self::$maxNetworkRetries;
    }
    /**
     * @param int $maxNetworkRetries Maximum number of request retries
     */
    public static function setMaxNetworkRetries($maxNetworkRetries)
    {
        self::$maxNetworkRetries = $maxNetworkRetries;
    }
    /**
     * @return float Maximum delay between retries, in seconds
     */
    public static function getMaxNetworkRetryDelay()
    {
        return self::$maxNetworkRetryDelay;
    }
    /**
     * @return float Initial delay between retries, in seconds
     */
    public static function getInitialNetworkRetryDelay()
    {
        return self::$initialNetworkRetryDelay;
    }
}
