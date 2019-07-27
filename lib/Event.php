<?php

namespace FedaPay;

/**
 * Class Event
 *
 * @property int $id
 * @property string $type
 * @property string $entity
 * @property int $object_id
 * @property int $account_id
 * @property string $object
 * @property string $created_at
 * @property string $updated_at
 *
 * @package FedaPay
 */
class Event extends Resource
{
    use ApiOperations\All;
    use ApiOperations\Retrieve;
}
