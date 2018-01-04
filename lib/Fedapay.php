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
    public static $apiKey;

    // @var string The base URL for the Fedapay API.
    public static $apiBase = 'https://api.fedapay.com';

    // @var string|null The version of the Fedapay API to use for requests.
    public static $apiVersion = null;

    // @var string|null The account ID for connected accounts requests.
    public static $accountId = null;

    const VERSION = '1.0.0';

    const API_VERSION = 'v1';

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
     */
    public static function setApiKey($apiKey)
    {
        self::$apiKey = $apiKey;
    }

    /**
     * @return string The API version used for requests. null if we're using the
     *    latest version.
     */
    public static function getApiVersion()
    {
        return self::$apiVersion;
    }

    /**
     * @param string $apiVersion The API version to use for requests.
     */
    public static function setApiVersion($apiVersion)
    {
        self::$apiVersion = $apiVersion;
    }

    /** @return string | null The Fedapay account ID for connected account
     *   requests.
     */
    public static function getAccountId()
    {
        return self::$accountId;
    }

    /**
     * @param string $accountId The Fedapay account ID to set for connected
     *   account requests.
     */
    public static function setAccountId($accountId)
    {
        self::$accountId = $accountId;
    }
}
