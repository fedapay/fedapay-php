<?php

namespace FedaPay;

use FedaPay\Util\Util;

/**
 * Class FedaPayObject
 *
 * @package FedaPay
 */
class FedaPayObject implements \ArrayAccess, \JsonSerializable
{
    protected $_values;

    public function __construct($id = null, $opts = null)
    {
        $this->_values = [];

        if (is_array($id)) {
            $this->refreshFrom($id, $opts);
        } elseif ($id !== null) {
            $this->id = $id;
        }
    }

    // Standard accessor magic methods
    public function __set($k, $v)
    {
        if ($v === '') {
            throw new \InvalidArgumentException(
                'You cannot set \''.$k.'\'to an empty string. '
                .'We interpret empty strings as NULL in requests. '
                .'You may set obj->'.$k.' = NULL to delete the property'
            );
        }

        $this->_values[$k] = $v;
    }

    public function &__get($k)
    {
        // function should return a reference, using $nullval to return a reference to null
        $nullval = null;
        if (!empty($this->_values) && array_key_exists($k, $this->_values)) {
            return $this->_values[$k];
        } else {
            $class = get_class($this);
            error_log("FedaPay Notice: Undefined property of $class instance: $k");
            return $nullval;
        }
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
    public function offsetSet(mixed $k, mixed $v): void
    {
        $this->$k = $v;
    }

    public function offsetExists(mixed $k): bool
    {
        return array_key_exists($k, $this->_values);
    }

    public function offsetUnset(mixed $k): void
    {
        unset($this->$k);
    }

    public function offsetGet(mixed $k): mixed
    {
        return array_key_exists($k, $this->_values) ? $this->_values[$k] : null;
    }

    public function keys(): array
    {
        return array_keys($this->_values);
    }

    public function jsonSerialize(): mixed
    {
        return $this->_values;
    }

    public function __toJSON()
    {
        return json_encode($this->__toArray(true), JSON_PRETTY_PRINT);
    }

    public function __toString(): string
    {
        $class = get_class($this);
        return $class . ' JSON: ' . $this->__toJSON();
    }

    public function __toArray($recursive = false): array
    {
        if ($recursive) {
            return Util::convertFedaPayObjectToArray($this->_values);
        } else {
            return $this->_values;
        }
    }

    public function serializeParameters(): array
    {
        $params = [];

        foreach ($this->_values as $key => $value) {
            if ($key === 'id') {
                continue;
            }

            if ($value instanceof FedaPayObject) {
                $serialized = $value->serializeParameters();
                if ($serialized) {
                    $params[$key] = $serialized;
                }
            } else {
                $params[$key] = $value;
            }
        }

        return $params;
    }

    public function refreshFrom($values, $opts)
    {
        if (!is_null($values)) {
            if ($values instanceof FedaPayObject) {
                $values = $values->__toArray(true);
            }

            foreach ($values as $k => $value) {
                if (is_array($value)) {
                    $k = Util::stripApiVersion($k, $opts);
                    $this->_values[$k] = Util::arrayToFedaPayObject($value, $opts);
                } else {
                    $this->_values[$k] = $value;
                }
            }
        }
    }
}
