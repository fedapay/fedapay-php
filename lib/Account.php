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
}
