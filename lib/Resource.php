<?php

namespace Fedapay;

/**
 * Class Resource
 *
 * @package Fedapay
 */
abstract class Resource extends FedapayObject
{
    public static function baseUrl()
    {
        return null;
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

        if (substr($class, 0, strlen('Fedapay')) == 'Fedapay') {
            $class = substr($class, strlen('Fedapay'));
        }

        $class = str_replace('_', '', $class);
        $name = urlencode($class);
        $name = strtolower($name);

        return $name;
    }

    /**
     * @return string The endpoint URL for the given class.
     */
    public static function classUrl()
    {
        $base = static::className();
        return '/' . Fedapay::API_VERSION . "/${base}s";
    }

    /**
     * @return string The instance endpoint URL for the given class.
     */
    public static function resourceUrl($id)
    {
        if ($id === null) {
            $class = get_called_class();
            $message = 'Could not determine which URL to request: '
               . "$class instance has invalid ID: $id";
            throw new Error\InvalidRequest($message, null);
        }

        $base = static::classUrl();
        $extn = urlencode($id);

        return "$base/$extn";
    }

    /**
     * @return string The full API URL for this API resource.
     */
    public function instanceUrl()
    {
        return static::resourceUrl($this['id']);
    }

    protected static function _validateParams($params = null)
    {
        if ($params && !is_array($params)) {
            $message = "You must pass an array as the first argument to Fedapay API "
               . "method calls.  (HINT: an example call to create a customer "
               . "would be: \"Fedapay\\Customer::create(array('firstname' => toto, "
               . "'lastname' => 'zoro', 'email' => 'admin@gmail.com', 'phone' => '66666666'))\")";
            throw new Error\InvalidRequest($message);
        }
    }

    protected static function _staticRequest($method, $url, $params, $options)
    {
        $requestor = new Requestor(static::baseUrl());
        $response = $requestor->requestor($method, $url, $params, $options->headers);

        return $response;
    }

    protected static function _retrieve($id, $options = null)
    {
        $url = $this->instanceUrl();
        $response = static::_staticRequest('get', $url, $params, $options);

        return $response;
    }

    protected static function _all($params = null, $options = null)
    {
        self::_validateParams($params);
        $url = static::classUrl();
        $response = static::_staticRequest('get', $url, $params, $options);

        return $response;
    }

    protected static function _create($params = null, $options = null)
    {
        self::_validateParams($params);
        $url = static::classUrl();
        $response = static::_staticRequest('post', $url, $params, $options);

        return $response;
    }

    /**
     * @param string            $id     The ID of the API resource to update.
     * @param array|null        $params
     * @param array|string|null $opts
     *
     * @return Resource the updated API resource
     */
    protected static function _update($id, $params = null, $options = null)
    {
        self::_validateParams($params);
        $url = static::resourceUrl($id);
        $response = static::_staticRequest('post', $url, $params, $options);

        return $response;
    }

    /**
     * Send a detele request
     * @param  array $params
     * @param  array $options
     */
    protected function _delete($params = null, $options = null)
    {
        self::_validateParams($params);
        $url = $this->instanceUrl();
        $response = static::_staticRequest('delete', $url, $params, $options);

        return $this;
    }
}
