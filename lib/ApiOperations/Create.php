<?php

namespace FedaPay\ApiOperations;

/**
 * trait Create
 */
trait Create
{
    /**
     * Static method to create a resources
     * @param array $params
     * @param array $headers
     *
     * @return Resource
     */
    public static function create($params = [], $headers = [])
    {
        self::_validateParams($params);
        $url = static::classPath();
        $className = static::className();

        list($response, $opts) = static::_staticRequest('post', $url, $params, $headers);

        $object = \FedaPay\Util\Util::arrayToFedaPayObject($response, $opts);

        return $object->$className;
    }
}
