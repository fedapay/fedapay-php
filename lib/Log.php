<?php

namespace FedaPay;

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
 * @package FedaPay
 */
class Log extends Resource
{
    use ApiOperations\All;
    use ApiOperations\Retrieve;
}
