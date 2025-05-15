<?php

namespace Tests\OAuth;

use Tests\BaseTestCase;
use FedaPay\FedaPay;

class ClientTest extends BaseTestCase
{
    /**
     * Should return array of FedaPay\Account
     */
    public function testShouldSendTokenRequest()
    {
        $body = [
            'access_token' => 'oioio990',
            'token_type' => 'Bearer',
            'scope' => 'read_write',
            'created_at' => '1747346992'
        ];

        FedaPay::setOauthClientId('client id');
        FedaPay::setOauthClientSecret('client secret');
        $data = [
            'grant_type' => 'client_credentials',
            'client_id' => 'client id',
            'client_secret' => 'client secret'
        ];

        $this->mockRequest('post', '/v1/oauth/token', $data, $body);

        $object = \FedaPay\OAuth\Client::grantClientCredentials();

        $this->assertEquals('oioio990', $object->access_token);
        $this->assertEquals('Bearer', $object->token_type);
        $this->assertEquals('read_write', $object->scope);
        $this->assertEquals('1747346992', $object->created_at);
    }
}
