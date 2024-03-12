<?php

namespace FedaPay;

use FedaPay\Util\Util;

/**
 * Class Transaction
 *
 * @property int $id
 * @property string $reference
 * @property string $description
 * @property string $callback_url
 * @property string $amount
 * @property string $status
 * @property int $transaction_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @package FedaPay
 */
class Transaction extends Resource
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
     * Available Mobile Money modes for send now
     * @var array
     */
    private static $AVAILABLE_MOBILE_MONEY = [
        'mtn', 'moov', 'mtn_ci', 'moov_tg', 'mtn_open', 'airtel_ne', 'free_sn',
        'togocel', 'mtn_ecw'
    ];

    /**
     * Paid status list
     * @var array
     */
    private static $PAID_STATUS = [
        'approved', 'transferred', 'refunded',
        'approved_partially_refunded', 'transferred_partially_refunded'
    ];

    /**
     * Check the transaction mode for send now request
     *
     * @param string $mode
     * @return boolean
     * @throw \InvalidArgumentException
     */
    protected static function mobileMoneyModeAvailable($mode)
    {
        if (!in_array($mode, self::$AVAILABLE_MOBILE_MONEY)) {
            throw new \InvalidArgumentException(
                'Invalid payment method \''.$mode.'\' supplied. '
                .'You have to use one of the following payment methods '.
                '['. implode(',', self::$AVAILABLE_MOBILE_MONEY) .']'
            );
        }

        return true;
    }

    /**
     * Check if the transaction was paid
     *
     * @return boolean
     */
    public function wasPaid()
    {
        return in_array($this->status, self::$PAID_STATUS);
    }

    /**
     * Check if the transacton was refunded. Status must include refunded.
     *
     * @return boolean
     */
    public function wasRefunded()
    {
        return strpos($this->status, 'refunded') !== false;
    }

    /**
     * Check if the transacton was partially refunded. Status must include partially_refunded.
     *
     * @return boolean
     */
    public function wasPartiallyRefunded()
    {
        return strpos($this->status, 'partially_refunded') !== false;
    }

    /**
     * Generate a payment token and url
     * @return FedaPay\FedaPayObject
     */
    public function generateToken($params = [], $headers = [])
    {
        $url = $this->instanceUrl() . '/token';

        list($response, $opts) = static::_staticRequest('post', $url, $params, $headers);
        return Util::arrayToFedaPayObject($response, $opts);
    }

    /**
     * Send Mobile Money request with token
     * @param string $mode
     * @param string $token
     * @param array $params
     * @param array $headers
     *
     * @return FedaPay\FedaPayObject
     */
    public function sendNowWithToken($mode, $token, $params = [], $headers = [])
    {
        static::mobileMoneyModeAvailable($mode);

        $url = '/' . $mode;
        $params = array_merge(['token' => $token], $params);

        list($response, $opts) = static::_staticRequest('post', $url, $params, $headers);
        return Util::arrayToFedaPayObject($response, $opts);
    }

    /**
     * Return transaction receipt URL
     * @param array $force
     * @param array $params
     * @param array $headers
     *
     * @return string
     */
    public function getReceiptURL($force = false, $params = [], $headers = [])
    {
        $receipt_url = $this->receipt_url;

        if (is_null($receipt_url) || $force) {
            $url = $this->instanceUrl() . '/receipt_url';
            // Force Api to generate url
            if ($force) {
                $params['force'] = true;
            }

            list($response, $opts) = static::_staticRequest('post', $url, $params, $headers);
            $urlObject = Util::arrayToFedaPayObject($response, $opts);

            $receipt_url = $urlObject->url;
        }

        return $receipt_url;
    }

    /**
     * Send Mobile Money request
     * @param string $mode
     * @param array $params
     * @param array $headers
     *
     * @return FedaPay\FedaPayObject
     */
    public function sendNow($mode, $params = [], $headers = [])
    {
        $tokenObject = $this->generateToken([], $headers);

        return $this->sendNowWithToken($mode, $tokenObject->token, $params, $headers);
    }

    /**
     * Get transactions fees details
     * @param string $token
     * @param string $mode
     * @param array $params
     * @param array $headers
     *
     * @return FedaPay\FedaPayObject
     */
    public function getFees($token, $mode, $params = [], $headers = [])
    {
        $url = static::classPath() . '/fees';

        $params = array_merge(['token' => $token, 'mode' => $mode], $params);

        list($response, $opts) = static::_staticRequest('get', $url, $params, $headers);
        return Util::arrayToFedaPayObject($response, $opts);
    }
}
