<?php

namespace Fedapay;

/**
 * Class Customer
 *
 * @property int $id
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property string $created_at
 * @property string $phone
 *
 * @package Fedapay
 */
class Customer extends Resource {
    /**
     * @param array|string $id The ID of the customer to retrieve, or an
     *     options array containing an `id` key.
     * @param array|string|null $opts
     *
     * @return Customer
     */
    public static function retrieve($id, $opts = null)
    {
        return self::_retrieve($id, $opts);
    }

    /**
     * @param array|null $params
     * @param array|string|null $opts
     *
     * @return Collection of Customers
     */
    public static function all($params = null, $opts = null)
    {
        return self::_all($params, $opts);
    }

    /**
     * @param array|null $params
     * @param array|string|null $opts
     *
     * @return Customer The created customer.
     */
    public static function create($params = null, $opts = null)
    {
        return self::_create($params, $opts);
    }

    /**
     * @param string $id The ID of the customer to update.
     * @param array|null $params
     * @param array|string|null $options
     *
     * @return Customer The updated customer.
     */
    public static function update($id, $params = null, $options = null)
    {
        return self::_update($id, $params, $options);
    }

    /**
     * @param array|string|null $opts
     *
     * @return Customer The saved customer.
     */
    public function save($opts = null)
    {
        return $this->_save($opts);
    }

    /**
     * @param array|null $params
     * @param array|string|null $opts
     *
     * @return Customer The deleted customer.
     */
    public function delete($params = null, $opts = null)
    {
        return $this->_delete($params, $opts);
    }

}
