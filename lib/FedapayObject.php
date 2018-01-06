<?php

namespace Fedapay;

use Fedapay\Util\Util;

/**
 * Class FedapayObject
 *
 * @package Fedapay
 */
class FedapayObject implements \ArrayAccess, \JsonSerializable
{
    protected $_values;

    // Standard accessor magic methods
    public function __set($k, $v)
    {
        if ($v === "") {
            throw new \InvalidArgumentException(
                'You cannot set \''.$k.'\'to an empty string. '
                .'We interpret empty strings as NULL in requests. '
                .'You may set obj->'.$k.' = NULL to delete the property'
            );
        }

        $this->_values[$k] = $v;
    }

    public function __isset($k)
    {
        return isset($this->_values[$k]);
    }

    public function __unset($k)
    {
        unset($this->_values[$k]);
    }

    // Magic method for var_dump output. Only works with PHP >= 5.6
    public function __debugInfo()
    {
        return $this->_values;
    }

    // ArrayAccess methods
    public function offsetSet($k, $v)
    {
        $this->$k = $v;
    }

    public function offsetExists($k)
    {
        return array_key_exists($k, $this->_values);
    }

    public function offsetUnset($k)
    {
        unset($this->$k);
    }

    public function offsetGet($k)
    {
        return array_key_exists($k, $this->_values) ? $this->_values[$k] : null;
    }

    public function keys()
    {
        return array_keys($this->_values);
    }

    public function jsonSerialize()
    {
        return $this->__toArray(true);
    }

    public function __toJSON()
    {
        if (defined('JSON_PRETTY_PRINT')) {
            return json_encode($this->__toArray(true), JSON_PRETTY_PRINT);
        } else {
            return json_encode($this->__toArray(true));
        }
    }

    public function __toString()
    {
        $class = get_class($this);
        return $class . ' JSON: ' . $this->__toJSON();
    }

    public function __toArray()
    {
        return $this->_values;
    }

    public function refreshFrom($values, $opts) {
        foreach ($values as $k => $value) {
            if (is_array($value)) {
                $k = Util::stripApiVersion($k, $opts);
                $this->_values[$k] = Util::arrayToFedapayObject($value, $opts);
            } else {
                $this->_values[$k] = $value;
            }
        }
    }
}
