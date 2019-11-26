<?php

namespace FedaPay;

/**
 * Class Page
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
        $base = static::classPath();
        $extn = urlencode($reference);
        $url = "$base/$extn/verify";

        list($response, $opts) = static::_staticRequest('get', $url, $params, $headers);
        $object = \FedaPay\Util\Util::arrayToFedaPayObject($response, $opts);
        return $object->page_verify;
    }

}
