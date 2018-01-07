<?php

namespace Fedapay;

/**
 * Class Currency
 *
 * @property int $id
 * @property string $name
 * @property string $iso
 * @property int $code
 * @property string $prefix
 * @property string $suffix
 * @property string $div
 * @property string $created_at
 * @property string $updated_at
 *
 * @package Fedapay
 */
class Currency extends Resource {
    /**
     * @param array|string $id The ID of the currency to retrieve, or an
     *     options array containing an `id` key.
     * @param array|string|null $opts
     *
     * @return Currency
     */
    public static function retrieve($id, $opts = null)
    {
        return self::_retrieve($id, $opts);
    }

    /**
     * @param array|null $params
     * @param array|string|null $opts
     *
     * @return Collection of Currencys
     */
    public static function all($params = [], $headers = [])
    {
        return self::_all($params, $headers);
    }
}
