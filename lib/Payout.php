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
    use ApiOperations\All;
    use ApiOperations\Search;
    use ApiOperations\Retrieve;
    use ApiOperations\Create;
    use ApiOperations\CreateInBatch;
    use ApiOperations\Update;
    use ApiOperations\Save;
    use ApiOperations\Delete;

    /**
     * @param array|string|null $params
     * @param array|string|null $headers
     *
     * @return Payout.
     */
    protected function _start($params, $headers)
    {
        $path = static::resourcePath('start');

        list($response, $opts) = static::_staticRequest('put', $path, $params, $headers);

        $object =  Util::arrayToFedaPayObject($response, $opts);
        $this->refreshFrom($object->payouts[0], $opts);

        return $this;
    }

    /**
     * @param array|string|null $params
     * @param array|string|null $headers
     *
     * @return FedaPayObject.
     */
    protected static function _startAll($params, $headers)
    {
        $path = static::resourcePath('start');

        list($response, $opts) = static::_staticRequest('put', $path, $params, $headers);

        return Util::arrayToFedaPayObject($response, $opts);
    }

    /**
     * Start the payout
     * @param string $scheduled_at
     * @param array|string|null $params
     *
     * @return FedaPay\FedaPayObject
     */
    public function schedule($scheduled_at, $params = [], $headers = [])
    {
        $scheduled_at = Util::toDateString($scheduled_at);

        $payout_params = [ 'id' => $this->id, 'scheduled_at' => $scheduled_at ];

        if (isset($params['phone_number'])) {
            $payout_params['phone_number'] = $params['phone_number'];
            unset($params['phone_number']); // Remove phone_number from params
        }

        $_params = [ 'payouts' => [ $payout_params ] ];

        $params = array_merge($_params, $params);

        return $this->_start($params, $headers);
    }

    /**
     * Send the payout now
     * @param array|string|null $params
     * @param array|string|null $headers
     *
     * @return FedaPay\FedaPayObject
     */
    public function sendNow($params = [], $headers = [])
    {
        $payout_params = [ 'id' => $this->id ];

        if (isset($params['phone_number'])) {
            $payout_params['phone_number'] = $params['phone_number'];
            unset($params['phone_number']); // Remove phone_number from params
        }

        $_params = [ 'payouts' => [$payout_params] ];

        $params = array_merge($_params, $params);

        return $this->_start($params, $headers);
    }

    /**
     * Start a scheduled payout
     *
     * @param array $payouts list of payouts id to start. One at least
     * @param null|DateTime $scheduled_at If null, payouts should be send now.
     * @param array $headers
     * @return FedaPay\FedaPayObject
     */
    public static function scheduleAll($payouts = [], $params = [], $headers = [])
    {
        $items = [];

        foreach ($payouts as $key => $payout) {
            $item = [];
            if (!array_key_exists('id', $payout)) {
                throw new \InvalidArgumentException(
                    'Invalid id argument. You must specify payout id.'
                );
            }
            $item['id'] = $payout['id'];

            if (array_key_exists('scheduled_at', $payout)) {
                $item['scheduled_at'] = Util::toDateString($payout['scheduled_at']);
            }

            if (isset($params[$key]['phone_number'])) {
                $item['phone_number'] = $params[$key]['phone_number'];
                unset($params[$key]['phone_number']); // Remove phone_number from params
            }

            $items[] = $item;
        }

        $_params = [
            'payouts' => $items
        ];
        $params = array_merge($_params, $params);

        return self::_startAll($params, $headers);
    }

    /**
     * Send all payouts now
     *
     * @param array $payouts list of payouts id to start. One at least
     * @param array $params If null, payouts should be send now.
     * @param array $headers
     * @return FedaPay\FedaPayObject
     */
    public static function sendAllNow($payouts = [], $params = [], $headers = [])
    {
        $items = [];

        foreach ($payouts as $key => $payout) {
            $item = [];
            if (!array_key_exists('id', $payout)) {
                throw new \InvalidArgumentException(
                    'Invalid id argument. You must specify payout id.'
                );
            }
            $item['id'] = $payout['id'];

            if (isset($params[$key]['phone_number'])) {
                $item['phone_number'] = $params[$key]['phone_number'];
                unset($params[$key]['phone_number']); // Remove phone_number from params
            }

            $items[] = $item;
        }

        $_params = [
            'payouts' => $items
        ];
        $params = array_merge($_params, $params);

        return self::_startAll($params, $headers);
    }
}
