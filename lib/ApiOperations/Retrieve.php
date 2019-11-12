<?php

namespace FedaPay\ApiOperations;

/**
 * trait Retrieve
 */
trait Retrieve
{
    /**
     * Static method to retrive a resource
     * @param mixed $id
     * @return FedaPay\FedaPayObject
     */
    public static function retrieve($id, $params = [], $headers = [])
    {
        $url = static::resourcePath($id);
        $className = static::className();

        list($response, $opts) = static::_staticRequest('get', $url, $params, $headers);
        $object = \FedaPay\Util\Util::arrayToFedaPayObject($response, $opts);

        return $object->$className;
    }
}
