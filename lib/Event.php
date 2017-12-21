<?php

namespace Fedapay;

/**
 * Class Event
 *
 * @property int $id
 * @property mixed $data
 * @property string $type
 * @property string $created 
 *
 * @package Fedapay
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
