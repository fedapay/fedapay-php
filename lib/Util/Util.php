<?php
namespace FedaPay\Util;

use FedaPay\FedaPayObject;

/**
 * Class Util
 *
 * @package FedaPay\Util
 */
abstract class Util
{
    private static $isMbstringAvailable = null;
    private static $isHashEqualsAvailable = null;

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

    /**
     * Convert a a response to fedapay object
     * @param array $resp The response object
     * @param array $opts Additional options.
     *
     * @return \FedaPay\FedaPayObject
     */
    public static function convertToFedaPayObject($resp, $opts)
    {
        $types = [
            'v1/api_key' => 'FedaPay\\ApiKey',
            'v1/account' => 'FedaPay\\Account',
            'v1/currency' => 'FedaPay\\Currency',
            'v1/customer' => 'FedaPay\\Customer',
            'v1/event' => 'FedaPay\\Event',
            'v1/event_type' => 'FedaPay\\EventType',
            'v1/invitation' => 'FedaPay\\Invitation',
            'v1/log' => 'FedaPay\\Log',
            'v1/phone_number' => 'FedaPay\\PhoneNumber',
            'v1/role' => 'FedaPay\\Role',
            'v1/setting' => 'FedaPay\\Setting',
            'v1/transaction' => 'FedaPay\\Transaction',
            'v1/user' => 'FedaPay\\User',
            'v1/payout' => 'FedaPay\\Payout',
        ];
        $class = FedaPayObject::class;

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

    /**
     * Converts an array to FedaPay object.
     *
     * @param array $array The PHP array to convert.
     * @param array $opts Additional options.
     *
     * @return \FedaPay\FedaPayObject
     */
    public static function arrayToFedaPayObject($array, $opts)
    {
        if (self::isList($array)) {
            $mapped = array();
            foreach ($array as $i) {
                array_push($mapped, self::convertToFedaPayObject($i, $opts));
            }

            return $mapped;
        } else {
            return self::convertToFedaPayObject($array, $opts);
        }
    }

    /**
     * Recursively converts the PHP FedaPay object to an array.
     *
     * @param array $values The PHP FedaPay object to convert.
     * @return array
     */
    public static function convertFedaPayObjectToArray($values)
    {
        $results = [];

        foreach ($values as $k => $v) {
            if ($v instanceof FedaPayObject) {
                $results[$k] = $v->__toArray(true);
            } elseif (is_array($v)) {
                $results[$k] = self::convertFedaPayObjectToArray($v);
            } else {
                $results[$k] = $v;
            }
        }

        return $results;
    }

    /**
     * Strip api version from key
     * @param string $key
     * @param array $opts
     *
     * @return string
     */
    public static function stripApiVersion($key, $opts)
    {
        $apiPart = '';
        if (is_array($opts) && isset($opts['apiVersion'])) {
            $apiPart = $opts['apiVersion'] . '/';
        }

        return str_replace($apiPart, '', $key);
    }

    /**
     * Check a date falue
     * @param mixed $date
     * @return mixed
     */
    public static function toDateString($date)
    {
        if ($date instanceof \DateTime) {
            return $date->format('Y-m-d H:i:s');
        } else if (is_string($date) || is_int($date)) {
            return $date;
        } else {
            throw new \InvalidArgumentException(
                'Invalid datetime argument. Should be a date in string format, '
                .' a timestamp  or an instance of \DateTime.'
            );
        }
    }
}
