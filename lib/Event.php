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
    /**
     * @param array|string $id The ID of the event to retrieve, or an options
     *     array containing an `id` key.
     * @param array|string|null $opts
     *
     * @return Event
     */
    public static function retrieve($id, $opts = null)
    {
        return self::_retrieve($id, $opts);
    }

    /**
     * @param array|null $params
     * @param array|string|null $opts
     *
     * @return Collection of Events
     */
    public static function all($params = null, $opts = null)
    {
        return self::_all($params, $opts);
    }
}
