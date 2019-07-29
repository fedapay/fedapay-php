<?php

namespace FedaPay;

use FedaPay\Util\Util;
use FedaPay\Util\Inflector;

/**
 * Class Resource
 *
 * @package FedaPay
 */
abstract class Resource extends FedaPayObject
{
    use ApiOperations\Request;

    /**
     * @var FedaPay\Requestor
     */
    protected static $requestor;

    /**
     * Set requestor
     * @param FedaPay\Requestor $requestor
     */
    public static function setRequestor(Requestor $requestor)
    {
        self::$requestor = $requestor;
    }

    /**
     * Return the requestor
     * @return FedaPay\Requestor
     */
    public static function getRequestor()
    {
        return self::$requestor ?: new Requestor;
    }

    public static function className()
    {
        $class = get_called_class();
        // Useful for namespaces: Foo\Charge
        if ($postfixNamespaces = strrchr($class, '\\')) {
            $class = substr($postfixNamespaces, 1);
        }

        // Useful for underscored 'namespaces': Foo_Charge
        if ($postfixFakeNamespaces = strrchr($class, '')) {
            $class = $postfixFakeNamespaces;
        }

        if (substr($class, 0, strlen('FedaPay')) == 'FedaPay') {
            $class = substr($class, strlen('FedaPay'));
        }

        $class = str_replace('_', '', $class);
        $name = urlencode($class);
        $name = strtolower($name);

        return $name;
    }

    /**
     * @return string The endpoint URL for the given class.
     */
    public static function classPath()
    {
        $base = static::className();
        $plurial = Inflector::pluralize($base);

        return "/$plurial";
    }

    /**
     * @return string The instance endpoint URL for the given class.
     */
    public static function resourcePath($id)
    {
        if ($id === null) {
            $class = get_called_class();
            $message = 'Could not determine which URL to request: '
               . "$class instance has invalid ID: $id";
            throw new Error\InvalidRequest($message, null);
        }

        $base = static::classPath();
        $extn = urlencode($id);

        return "$base/$extn";
    }

    /**
     * @return string The full API URL for this API resource.
     */
    public function instanceUrl()
    {
        return static::resourcePath($this['id']);
    }
}
