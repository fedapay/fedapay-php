<?php

namespace Fedapay;

/**
 * Class Fedapay
 *
 * @package Fedapay
 */
class Fedapay
{
    // @var string The Fedapay API key to be used for requests.
    protected static $apiKey;

    // @var string The environment for the Fedapay API.
    protected static $environment = 'sandbox';

    protected static $apiVersion = 'v1';

    const VERSION = '1.0.0';

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
