<?php

namespace Fedapay;

/**
 * Class Account
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
class Account extends Resource {
    /**
     * @param array|string $id The ID of the account to retrieve.
     * @param array|string|null $headers
     *
     * @return Account
     */
    public static function retrieve($id, $headers = null)
    {
        return self::_retrieve($id, $headers);
    }

    /**
     * @param array|null $params
     * @param array|null $headers
     *
     * @return Collection of Accounts
     */
    public static function all($params = [], $headers = [])
    {
        return self::_all($params, $headers);
    }
}
