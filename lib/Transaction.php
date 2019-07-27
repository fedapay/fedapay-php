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
    use ApiOperations\Retrieve;
    use ApiOperations\Create;
    use ApiOperations\Update;
    use ApiOperations\Save;
    use ApiOperations\Delete;

    private static $availableMobileMoney = ['mtn', 'moov', 'mtn_ci'];

    protected function modeAvailable($mode)
    {
        return in_array($mode, self::$availableMobileMoney);
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
     * @return FedaPay\FedaPayObject
     */
    public function sendNowWithToken($mode, $token, $params = [], $headers = [])
    {
        if (!$this->modeAvailable($mode)) {
            throw new \InvalidArgumentException(
                'Invalid payment method \''.$mode.'\' supplied. '
                .'You have to use one of the following payment methods '.
                '['. implode(self::$availableMobileMoney, ',') .']'
            );
        }

        $url = '/' . $mode;
        $params = array_merge(['token' => $token], $params);

        list($response, $opts) = static::_staticRequest('post', $url, $params, $headers);
        return Util::arrayToFedaPayObject($response, $opts);
    }

    /**
     * Send Mobile Money request
     * @return FedaPay\FedaPayObject
     */
    public function sendNow($mode, $params = [], $headers = [])
    {
        $tokenObject = $this->generateToken([], $headers);

        return $this->sendNowWithToken($mode, $tokenObject->token, $params, $headers);
    }
}
