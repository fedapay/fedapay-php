<?php

namespace FedaPay;

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
 * @package FedaPay
 */
class Currency extends Resource
{
    use ApiOperations\All;
    use ApiOperations\Retrieve;
}
