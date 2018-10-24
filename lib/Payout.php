<?php

namespace FedaPay;

use FedaPay\Util\Util;

/**
 * Class Payout
 *
 * @property int $id
 * @property string $reference
 * @property string $amount
 * @property string $status
 * @property int $customer_id
 * @property int $balance_id
 * @property string $mode
 * @property int $last_error_code
 * @property string $last_error_message
 * @property string $created_at
 * @property string $updated_at
 * @property string $scheduled_at
 * @property string $sent_at
 * @property string $failed_at
 * @property string $deleted_at
 *
 * @package FedaPay
 */
class Payout extends Resource
{
    /**
     * @param array|string $id The ID of the payout to retrieve
     * @param array|null $headers
     *
     * @return Payout
     */
    public static function retrieve($id, $headers = [])
    {
        return self::_retrieve($id, $headers);
    }

    /**
     * @param array|null $params
     * @param array|string|null $headers
     *
     * @return Collection of Payouts
     */
    public static function all($params = [], $headers = [])
    {
        return self::_all($params, $headers);
    }

    /**
     * @param array|null $params
     * @param array|string|null $headers
     *
     * @return Payout The created payout.
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
     * @return Payout The updated payout.
     */
    public static function update($id, $params = [], $headers = [])
    {
        return self::_update($id, $params, $headers);
    }

    /**
     * @param array|string|null $headers
     *
     * @return Payout The saved payout.
     */
    public function save($headers = [])
    {
        return $this->_save($headers);
    }

    /**
     * @param array $headers
     *
     * @return void
     */
    public function delete($headers = [])
    {
        return $this->_delete($headers);
    }

    /**
     * Start a scheduled payout
     * @return FedaPay\FedaPayObject
     */

    /**
     * Start a scheduled payout
     *
     * @param array $payouts list of payouts id to start. One at least
     * @param null|DateTime $scheduled_at If null, payouts should be send now.
     * @param array $headers
     * @return FedaPay\FedaPayObject
     */
    public static function start($payouts = [], $scheduled_at = null, $headers = [])
    {
        $url = static::resourcePath('start');
        $params = [
            'payouts' => $payouts
        ];

        if ($scheduled_at === null) {
            $params['send_now'] = true;
        } else {
            $params['scheduled_at'] = $scheduled_at;
        }

        list($response, $opts) = static::_staticRequest('put', $url, $params, $headers);
        return Util::arrayToFedaPayObject($response, $opts);
    }
}
