<?php

namespace FedaPay;

/**
 * Class Page
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $reference
 * @property string $published
 * @property string $amount
 * @property string $enable_phone_number
 * @property string $callback_url
 * @property array $custom_fields
 * @property string $image_url
 * @property int $account_id
 * @property int $currency_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 *
 * @package FedaPay
 */
class Page extends Resource
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

        return $object->page_verify;
    }
}
