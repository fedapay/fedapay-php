<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use FedaPay\HttpClient\CurlClient;

abstract class BaseTestCase extends TestCase
{
    protected $defaultHeaders = [];

    const API_KEY = 'sk_local_123';
    const OAUTH_TOKEN = 'oauth_test_token_123';
    const API_BASE = 'https://dev-api.fedapay.com';

    /** @before */
    protected function setUpConfig()
    {
        \FedaPay\FedaPay::setApiKey(self::API_KEY);
        \FedaPay\FedaPay::setApiBase(self::API_BASE);

        \FedaPay\Requestor::setHttpClient(\FedaPay\HttpClient\CurlClient::instance());
        $this->defaultHeaders = [
            'X-Version' => \FedaPay\FedaPay::VERSION,
            'X-Api-Version' => \FedaPay\FedaPay::getApiVersion(),
            'X-Source' => 'FedaPay PhpLib',
            'Authorization' => 'Bearer '. (self::API_KEY ?: self::OAUTH_TOKEN)
        ];
    }

    /** @after */
    protected function tearDownConfig()
    {
        // Back to default
        \FedaPay\FedaPay::setApiKey(null);
        \FedaPay\FedaPay::setApiVersion('v1');
        \FedaPay\FedaPay::setEnvironment('sandbox');
        \FedaPay\FedaPay::setToken(null);
        \FedaPay\FedaPay::setAccountId(null);
        \FedaPay\FedaPay::setVerifySslCerts(true);
        \FedaPay\FedaPay::setLocale(null);
        \FedaPay\Requestor::setHttpClient(null);
    }

    protected function mockRequest(
        $method,
        $path,
        $params = [],
        $response = [],
        $rcode = 200,
        $headers = []
    ) {
        $mock = $this->setUpMockRequest();
        $base = \FedaPay\FedaPay::getApiBase();
        $absUrl = $base . $path;
        $headers = array_merge($this->defaultHeaders, $headers);

        $rawHeaders = [];

        foreach ($headers as $k => $v) {
            $rawHeaders[] = $k . ': ' . $v;
        }

        if (is_array($response)) {
            $response = json_encode($response);
        }

        $mock->expects($this->once())
             ->method('request')
             ->with(
                 strtolower($method),
                 $absUrl,
                 $params,
                 $rawHeaders
             )
             ->willReturn([$response, $rcode, []]);
    }

    protected function mockMultipleRequests($requests)
    {
        $mock = $this->setUpMockRequest();
        $base = \FedaPay\FedaPay::getApiBase();
        $withs = [];
        $returns = [];

        foreach ($requests as $req) {
            $req = array_merge(['params' => [], 'response' => [], 'rcode' => 200, 'headers' => []], $req);

            $absUrl = $base . $req['path'];
            $headers = array_merge($this->defaultHeaders, $req['headers']);

            $rawHeaders = [];

            foreach ($headers as $k => $v) {
                $rawHeaders[] = $k . ': ' . $v;
            }

            if (is_array($req['response'])) {
                $response = json_encode($req['response']);
            }

            $withs[] = [
                strtolower($req['method']),
                $absUrl,
                $req['params'],
                $rawHeaders,
                [$response, $req['rcode'], []]
            ];
        }

        $mock->expects($this->exactly(count($requests)))
             ->method('request')
             ->will($this->returnValueMap($withs));
    }

    protected function setUpMockRequest()
    {
        $mock = $this->getMockBuilder('\FedaPay\HttpClient\ClientInterface')
                            ->setMethods(['request'])
                            ->getMock();

        \FedaPay\Requestor::setHttpClient($mock);

        return $mock;
    }
}
