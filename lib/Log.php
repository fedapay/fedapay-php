<?php

namespace Fedapay;

/**
 * Class Log
 *
 * @property int $id
 * @property string $method
 * @property string $url
 * @property string $status
 * @property string $ip_address
 * @property string $version
 * @property string $source
 * @property string $query
 * @property string $body
 * @property string $response
 * @property int $account_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @package Fedapay
 */
class Log extends Resource
{
    /**
     * @param array|string $id The ID of the log to retrieve, or an options
     *     array containing an `id` key.
     * @param array|string|null $opts
     *
     * @return Log
     */
    public static function retrieve($id, $opts = null)
    {
        return self::_retrieve($id, $opts);
    }

    /**
     * @param array|null $params
     * @param array|string|null $opts
     *
     * @return Collection of Logs
     */
    public static function all($params = null, $opts = null)
    {
        return self::_all($params, $opts);
    }
}
