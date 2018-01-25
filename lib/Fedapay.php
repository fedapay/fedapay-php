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

    const DEFAULT_CA_BUNDLE_PATH = __DIR__ . '/../data/ca-certificates.crt';

    // @var string The Fedapay API key to be used for requests.
    protected static $apiKey;

    // @var string The environment for the Fedapay API.
    protected static $environment = 'sandbox';

    protected static $apiVersion = 'v1';

    protected static $verifySslCerts = true;

    protected static $caBundlePath = self::DEFAULT_CA_BUNDLE_PATH;

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
     * @return string The API version used for requests.
     */
    public static function getApiVersion()
    {
        return self::$apiVersion;
    }

    /**
     * @param string $environment The API environment.
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
