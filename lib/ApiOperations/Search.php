<?php

namespace FedaPay\ApiOperations;

/**
 * trait Search
 */
trait Search
{
    /**
     * Static method to search resources
     * @param array $params
     * @param array $headers
     *
     * @return array FedaPay\FedaPayObject
     */
    public static function search($q = '*', $params = [], $headers = [])
    {
        $params['search'] = $q;
        self::_validateParams($params);
        $path = static::resourcePath('search');
        list($response, $opts) = static::_staticRequest('get', $path, $params, $headers);

        return \FedaPay\Util\Util::arrayToFedaPayObject($response, $opts);
    }
}
