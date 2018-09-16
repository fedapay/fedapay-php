<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use FedaPay\HttpClient\CurlClient;

abstract class BaseTestCase extends TestCase
{
    protected $headers = [];

    const API_KEY = 'sk_local_123';
    const OAUTH_TOKEN = 'oauth_test_token_123';
    const API_BASE = 'https://dev-api.fedapay.com';

    protected function setUp()
    {
        \FedaPay\FedaPay::setApiKey(self::API_KEY);
        \FedaPay\FedaPay::setApiBase(self::API_BASE);

        \FedaPay\Requestor::setHttpClient(\FedaPay\HttpClient\CurlClient::instance());
        $this->headers = [
            'X-Version' => \FedaPay\FedaPay::VERSION,
            'X-Source' => 'FedaPay PhpLib',
            'Authorization' => 'Bearer '. (self::API_KEY ?: self::OAUTH_TOKEN)
        ];
    }

    protected function tearDown()
    {
        // Back to default
        \FedaPay\FedaPay::setApiKey(null);
        \FedaPay\FedaPay::setApiVersion('v1');
        \FedaPay\FedaPay::setEnvironment('sandbox');
        \FedaPay\FedaPay::setToken(null);
        \FedaPay\FedaPay::setAccountId(null);
        \FedaPay\FedaPay::setVerifySslCerts(true);
        \FedaPay\Requestor::setHttpClient(null);
    }

    protected function mockRequest(
        $method,
        $path,
        $params = [],
        $response = [],
        $rcode = 200,
        $headers = []
    )
    {
        $mock = $this->setUpMockRequest();
        $base = \FedaPay\FedaPay::getApiBase();
        $absUrl = $base . $path;

        $mock->expects($this->once())
             ->method('request')
             ->with(
                 strtolower($method),
                 $absUrl,
                 $params,
                 array_merge($this->headers, $headers)
             )
             ->willReturn([json_encode($response), $rcode, []]);
    }

    private function setUpMockRequest()
    {
        $mock = $this->getMockBuilder('\FedaPay\HttpClient\ClientInterface')
                            ->setMethods(['request'])
                            ->getMock();

        \FedaPay\Requestor::setHttpClient($mock);

        return $mock;
    }
}
