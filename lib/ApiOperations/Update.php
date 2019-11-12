<?php

namespace FedaPay\ApiOperations;

/**
 * trait Update
 */
trait Update
{
    /**
     * Static method to update a resource
     * @param string $id     The ID of the API resource to update.
     * @param array $params The request params
     * @param array $headers the request headers
     *
     * @return Resource the updated API resource
     */
    public static function update($id, $params = [], $headers = [])
    {
        self::_validateParams($params);
        $path = static::resourcePath($id);
        $className = static::className();

        list($response, $opts) = static::_staticRequest('put', $path, $params, $headers);
        $object = \FedaPay\Util\Util::arrayToFedaPayObject($response, $opts);

        return $object->$className;
    }
}
