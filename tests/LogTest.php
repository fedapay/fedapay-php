<?php

namespace Tests;

class LogTest extends BaseTestCase
{
    /**
     * Should return array of FedaPay\Log
     */
    public function testShouldReturnLogs()
    {
        $body = [
            'v1/logs' => [[
                'id' => 1,
                'klass' => 'v1/log',
                'method' => 'GET',
                'url' => '/url',
                'status' => 200,
                'ip_address' => '189.2.33.9',
                'version' => '0.1.1',
                'source' => 'FedaPay PhpLib',
                'query' => '{"q":"search"}',
                'body' => '{}',
                'response' => '{}',
                'account_id' => 1,
                'created_at' => '2018-03-12T09:09:03.969Z',
                'updated_at' => '2018-03-12T09:09:03.969Z'
            ]],
            'meta' => ['page' => 1]
        ];

        $this->mockRequest('get', '/v1/logs', [], $body);

        $object = \FedaPay\Log::all();

        $this->assertInstanceOf(\FedaPay\FedaPayObject::class, $object);
        $this->assertInstanceOf(\FedaPay\FedaPayObject::class, $object->meta);
        $this->assertTrue(is_array($object->logs));
        $this->assertInstanceOf(\FedaPay\Log::class, $object->logs[0]);
        $this->assertEquals(1, $object->logs[0]->id);
        $this->assertEquals('GET', $object->logs[0]->method);
        $this->assertEquals('/url', $object->logs[0]->url);
        $this->assertEquals(200, $object->logs[0]->status);
        $this->assertEquals('189.2.33.9', $object->logs[0]->ip_address);
        $this->assertEquals('0.1.1', $object->logs[0]->version);
        $this->assertEquals('FedaPay PhpLib', $object->logs[0]->source);
        $this->assertEquals('{"q":"search"}', $object->logs[0]->query);
        $this->assertEquals('{}', $object->logs[0]->body);
        $this->assertEquals('{}', $object->logs[0]->response);
        $this->assertEquals(1, $object->logs[0]->account_id);
    }

    /**
     * Should retrieve a Log
     */
    public function testShouldRetrievedALog()
    {
        $body = [
            'v1/log' => [
                'id' => 1,
                'klass' => 'v1/log',
                'method' => 'GET',
                'url' => '/url',
                'status' => 200,
                'ip_address' => '189.2.33.9',
                'version' => '0.1.1',
                'source' => 'FedaPay PhpLib',
                'query' => '{"q":"search"}',
                'body' => '{}',
                'response' => '{}',
                'account_id' => 1,
                'created_at' => '2018-03-12T09:09:03.969Z',
                'updated_at' => '2018-03-12T09:09:03.969Z'
            ]
        ];

        $this->mockRequest('get', '/v1/logs/1', [], $body);

        $log = \FedaPay\Log::retrieve(1);

        $this->assertInstanceOf(\FedaPay\Log::class, $log);
        $this->assertEquals(1, $log->id);
        $this->assertEquals('GET', $log->method);
        $this->assertEquals('/url', $log->url);
        $this->assertEquals(200, $log->status);
        $this->assertEquals('189.2.33.9', $log->ip_address);
        $this->assertEquals('0.1.1', $log->version);
        $this->assertEquals('FedaPay PhpLib', $log->source);
        $this->assertEquals('{"q":"search"}', $log->query);
        $this->assertEquals('{}', $log->body);
        $this->assertEquals('{}', $log->response);
        $this->assertEquals(1, $log->account_id);
    }
}
