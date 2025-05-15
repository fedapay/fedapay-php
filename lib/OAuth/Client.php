<?php

namespace FedaPay\OAuth;

use FedaPay\Resource;
use FedaPay\FedaPay;

/**
 * CurlClient from https://github.com/stripe/stripe-php
 */
class Client extends Resource
{
    public static function grantClientCredentials()
    {
        list($response, $opts) = static::_staticRequest(
            'post', '/oauth/token',
            [
                'grant_type' => 'client_credentials',
                'client_id' => FedaPay::getOauthClientId(),
                'client_secret' => FedaPay::getOauthClientSecret()
            ]
        );

        return \FedaPay\Util\Util::arrayToFedaPayObject($response, $opts);
    }
}
