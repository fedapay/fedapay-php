<?php

namespace FedaPay;

/**
 * Class Customer
 *
 * @property int $id
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property string $phone
 * @property string $created_at
 * @property string $updated_at
 *
 * @package FedaPay
 */
class Customer extends Resource
{
    /**
     * @param array|string $id The ID of the customer to retrieve
     * @param array|null $headers
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
     * @param array|string|null $headers
     *
     * @return Customer The updated customer.
     */
    public static function update($id, $params = [], $headers = [])
    {
        return self::_update($id, $params, $headers);
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
     * @param array $headers
     *
     * @return Customer The deleted customer.
     */
    public function delete($headers = [])
    {
        return $this->_delete($headers);
    }
}
