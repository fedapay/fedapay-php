<?php

namespace FedaPay;

/**
 * Class Invoice
 *
 * @property int $id
 * @property integer $number
 * @property string $reference
 * @property string $status
 * @property integer $tax
 * @property string $discount_type
 * @property integer $discount_amount
 * @property integer $ttc
 * @property integer $sub_total
 * @property integer $discount
 * @property integer $before_tax
 * @property integer $tax_amount
 * @property integer $total_amount_paid
 * @property string $notes
 * @property integer $invoice_products_count
 * @property string $due_at
 * @property string $sent_at
 * @property array $paid_at
 * @property string $partially_paid_at
 * @property int $customer_id
 * @property int $currency_id
 * @property int $account_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 *
 * @package FedaPay
 */
class Invoice extends Resource
{
    use ApiOperations\All;
    use ApiOperations\Retrieve;
    use ApiOperations\Create;
    use ApiOperations\Update;
    use ApiOperations\Save;
    use ApiOperations\Delete;

    public static function verify($reference, $params = [], $headers = [])
    {
        $base = static::resourcePath($reference);
        $url = "$base/verify";

        list($response, $opts) = static::_staticRequest('get', $url, $params, $headers);
        $object = \FedaPay\Util\Util::arrayToFedaPayObject($response, $opts);

        return $object->invoice_verify;
    }
}
