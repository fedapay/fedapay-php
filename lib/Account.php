<?php

namespace FedaPay;

/**
 * Class Account
 *
 * @property int $id
 * @property string $name
 * @property string $timezone
 * @property string $country
 * @property string $verify
 * @property string $created_at
 * @property string $updated_at
 *
 * @package FedaPay
 */
class Account extends Resource
{
    use ApiOperations\All;
    use ApiOperations\Retrieve;
    use ApiOperations\Create;
    use ApiOperations\Update;
    use ApiOperations\Save;
    use ApiOperations\Delete;

    public static function light($params = [], $headers = [])
    {
        $path = static::resourcePath('light');
        $className = static::className();

        list($response, $opts) = static::_staticRequest('get', $path, $params, $headers);
        $object = \FedaPay\Util\Util::arrayToFedaPayObject($response, $opts);

        return $object->$className;
    }
}
