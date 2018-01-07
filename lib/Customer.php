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
     * @param array|string|null $headers
     *
     * @return Customer
     */
    public static function retrieve($id, $headers = [])
    {
        return self::_retrieve($id, $headers);
    }

    /**
     * @param array|null $params
     * @param array|string|null $headers
     *
     * @return Collection of Customers
     */
    public static function all($params = [], $headers = [])
    {
        return self::_all($params, $headers);
    }

    /**
     * @param array|null $params
     * @param array|string|null $headers
     *
     * @return Customer The created customer.
     */
    public static function create($params = [], $headers = [])
    {
        return self::_create($params, $headers);
    }

    /**
     * @param string $id The ID of the customer to update.
     * @param array|null $params
     * @param array|string|null $options
     *
     * @return Customer The updated customer.
     */
    public static function update($id, $params = [], $options = null)
    {
        return self::_update($id, $params, $options);
    }

    /**
     * @param array|string|null $headers
     *
     * @return Customer The saved customer.
     */
    public function save($headers = [])
    {
        return $this->_save($headers);
    }

    /**
     * @param array|null $params
     * @param array|string|null $headers
     *
     * @return Customer The deleted customer.
     */
    public function delete($params = [], $headers = [])
    {
        return $this->_delete($params, $headers);
    }

}
