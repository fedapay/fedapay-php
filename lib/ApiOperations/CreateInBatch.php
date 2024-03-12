<?php

namespace FedaPay\ApiOperations;

/**
 * trait CreateInBatch
 */
trait CreateInBatch
{
    /**
     * Create resources in batch
     *
     * @param array $params
     * @param array $headers
     * @return FedaPay\FedaPayObject
     */
    public static function createInBatch($params = [], $headers = [])
    {
        $path = static::resourcePath('batch');
        $className = static::className();

        list($response, $opts) = static::_staticRequest('post', $path, $params, $headers);

        $object = \FedaPay\Util\Util::arrayToFedaPayObject($response, $opts);

        $data = "{$className}_batch";
        return $object->$data;
    }
}
