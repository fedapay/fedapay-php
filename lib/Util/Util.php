<?php
namespace Fedapay\Util;

use Fedapay\FedapayObject;

/**
 * Class Util
 *
 * @package Fedapay\Util
 */
abstract class Util
{
    /**
     * Whether the provided array (or other) is a list rather than a dictionary.
     *
     * @param  array|mixed $array
     * @return boolean True if the given object is a list.
     */
    public static function isList($array)
    {
        if (!is_array($array)) {
            return false;
        }

        foreach (array_keys($array) as $k) {
            if (!is_numeric($k)) {
                return false;
            }
        }
        return true;
    }


    public static function convertToFedapayObject($resp, $opts)
    {
        $types = [
            'v1/account' => 'Fedapay\\Account',
            'v1/currency' => 'Fedapay\\Currency',
            'v1/customer' => 'Fedapay\\Customer',
            'v1/event' => 'Fedapay\\Event',
            'v1/event_type' => 'Fedapay\\EventType',
            'v1/invitation' => 'Fedapay\\Invitation',
            'v1/log' => 'Fedapay\\Log',
            'v1/role' => 'Fedapay\\Role',
            'v1/setting' => 'Fedapay\\Setting',
            'v1/transaction' => 'Fedapay\\Transaction',
            'v1/user' => 'Fedapay\\User',
        ];
        $class = FedapayObject::class;

        if (isset($resp['klass'])) {
            $klass = $resp['klass'];

            if (isset($types[$klass])) {
                $class = $types[$klass];
            }
        }

        $object = new $class;
        $object->refreshFrom($resp, $opts);

        return $object;
    }

    public static function arrayToFedapayObject($array, $opts)
    {
        if (self::isList($array)) {
            $mapped = array();
            foreach ($array as $i) {
                array_push($mapped, self::convertToFedapayObject($i, $opts));
            }

            return $mapped;
        } else {
            return self::convertToFedapayObject($array, $opts);
        }
    }

    public static function stripApiVersion($key, $opts)
    {
        $apiPart = '';
        if (is_array($opts) && isset($opts['apiVersion'])) {
            $apiPart = $opts['apiVersion'] . '/';
        }

        return str_replace($apiPart, '', $key);
    }
}
