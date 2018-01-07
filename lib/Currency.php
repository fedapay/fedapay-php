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
     * @param array|string $id The ID of the currency to retrieve.
     * @param array|string|null $headers
     *
     * @return Currency
     */
    public static function retrieve($id, $headers = null)
    {
        return self::_retrieve($id, $headers);
    }

    /**
     * @param array|null $params
     * @param array|null $headers
     *
     * @return Collection of Currencys
     */
    public static function all($params = [], $headers = [])
    {
        return self::_all($params, $headers);
    }
}
