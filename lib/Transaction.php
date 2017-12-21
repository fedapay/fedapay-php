<?php

namespace Fedapay;

/**
 * Class Transaction
 *
 * @property int $id
 * @property string $reference
 * @property string $description
 * @property string $callback_url
 * @property string $amount
 * @property string $status
 * @property string $items
 * @property int $customer_id
 * @property string $created_at 
 *
 * @package Fedapay
 */
class Transaction extends Resource
{
    /**
     * @param array|string $id The ID of the payout to retrieve, or an options
     *     array containing an `id` key.
     * @param array|string|null $opts
     *
     * @return Transaction
     */
    public static function retrieve($id, $opts = null)
    {
        return self::_retrieve($id, $opts);
    }

    /**
     * @param array|null $params
     * @param array|string|null $opts
     *
     * @return Collection of Transactions
     */
    public static function all($params = null, $opts = null)
    {
        return self::_all($params, $opts);
    }

}
