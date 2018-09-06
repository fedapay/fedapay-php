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
            'v1/role' => 'FedaPay\\Role',
            'v1/setting' => 'FedaPay\\Setting',
            'v1/transaction' => 'FedaPay\\Transaction',
            'v1/user' => 'FedaPay\\User',
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

    public static function stripApiVersion($key, $opts)
    {
        $apiPart = '';
        if (is_array($opts) && isset($opts['apiVersion'])) {
            $apiPart = $opts['apiVersion'] . '/';
        }

        return str_replace($apiPart, '', $key);
    }

    /**
     * @param string|mixed $value A string to UTF8-encode.
     *
     * @return string|mixed The UTF8-encoded string, or the object passed in if
     *    it wasn't a string.
     */
    public static function utf8($value)
    {
        if (self::$isMbstringAvailable === null) {
            self::$isMbstringAvailable = function_exists('mb_detect_encoding');
            if (!self::$isMbstringAvailable) {
                trigger_error("It looks like the mbstring extension is not enabled. " .
                    "UTF-8 strings will not properly be encoded. Ask your system " .
                    "administrator to enable the mbstring extension, or write to " .
                    "support@fedapay.com if you have any questions.", E_USER_WARNING);
            }
        }
        if (is_string($value) && self::$isMbstringAvailable && mb_detect_encoding($value, "UTF-8", true) != "UTF-8") {
            return utf8_encode($value);
        } else {
            return $value;
        }
    }

    /**
     * @param array $arr A map of param keys to values.
     * @param string|null $prefix
     *
     * @return string A querystring, essentially.
     */
    public static function urlEncode($arr, $prefix = null)
    {
        if (!is_array($arr)) {
            return $arr;
        }
        $r = [];
        foreach ($arr as $k => $v) {
            if (is_null($v)) {
                continue;
            }
            if ($prefix) {
                if ($k !== null && (!is_int($k) || is_array($v))) {
                    $k = $prefix."[".$k."]";
                } else {
                    $k = $prefix."[]";
                }
            }
            if (is_array($v)) {
                $enc = self::urlEncode($v, $k);
                if ($enc) {
                    $r[] = $enc;
                }
            } else {
                $r[] = urlencode($k)."=".urlencode($v);
            }
        }
        return implode("&", $r);
    }

}
