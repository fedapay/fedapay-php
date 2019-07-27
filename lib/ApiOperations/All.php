<?php

namespace FedaPay\ApiOperations;

/**
 * trait All
 */
trait All
{
    /**
     * Static method to retrive a list of resources
     * @param array $params
     * @param array $headers
     *
     * @return array FedaPay\FedaPayObject
     */
    public static function all($params = [], $headers = [])
    {
        self::_validateParams($params);
        $path = static::classPath();
        list($response, $opts) = static::_staticRequest('get', $path, $params, $headers);

        return \FedaPay\Util\Util::arrayToFedaPayObject($response, $opts);
    }
}
