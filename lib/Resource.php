<?php

namespace Fedapay;
/**
 * Class Resource
 *
 * @package Fedapay
 */
abstract class Resource
{
    public static function baseUrl()
    {
        return Fedapay::$apiBase;
    }


    /**
     * @return string The endpoint URL for the given class.
     */
    public static function classUrl()
    {
        $base = static::className();
        return "/v1/${base}s";
    }
    /**
     * @return string The instance endpoint URL for the given class.
     */
    public static function resourceUrl($id)
    {
        if ($id === null) {
            $class = get_called_class();
            $message = "Could not determine which URL to request: "
               . "$class instance has invalid ID: $id";
            throw new Error\ErrorHandler($message, null);
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
            throw new Error\ErrorHandler($message);
        }
    }

    protected static function Request($method, $url, $params, $options)
    {
        try
          {
          $header = array(‘Authorization’=>’Bearer ‘ . $this->accessToken);
          $response = $this->client->get($url, array(‘headers’ => $header));
          $result = $response->getBody()->getContents();
          return $result;
          }
          catch (RequestException $e)
          {
          $response = $this->StatusCodeHandling($e);
          return $response;
          }
        $opts = Util\RequestOptions::parse($options);
        $requestor = new HttpClient($opts->apiKey, static::baseUrl());
    }
    protected static function _retrieve($id, $options = null)
    {
        $opts = Util\RequestOptions::parse($options);
        $instance = new static($id, $opts);
        return $instance;
    }
    protected static function _all($params = null, $options = null)
    {
        self::_validateParams($params);
        $url = static::classUrl();
        list($response, $opts) = static::_staticRequest('get', $url, $params, $options);
        return $obj;
    }
    protected static function _create($params = null, $options = null)
    {
        self::_validateParams($params);
        $url = static::classUrl();
        list($response, $opts) = static::_staticRequest('post', $url, $params, $options);
        return $obj;
    }
    /**
     * @param string $id The ID of the API resource to update.
     * @param array|null $params
     * @param array|string|null $opts
     *
     * @return Resource the updated API resource
     */
    protected static function _update($id, $params = null, $options = null)
    {
        self::_validateParams($params);
        $url = static::resourceUrl($id);
        list($response, $opts) = static::_staticRequest('post', $url, $params, $options);
        return $obj;
    }
    protected function _save($options = null)
    {
        $params = $this->serializeParameters();
        if (count($params) > 0) {
            $url = $this->instanceUrl();
            list($response, $opts) = $this->_request('post', $url, $params, $options);
            $this->refreshFrom($response, $opts);
        }
        return $this;
    }
    protected function _delete($params = null, $options = null)
    {
        self::_validateParams($params);
        $url = $this->instanceUrl();
        list($response, $opts) = $this->_request('delete', $url, $params, $options);
        $this->refreshFrom($response, $opts);
        return $this;
    }

  }
