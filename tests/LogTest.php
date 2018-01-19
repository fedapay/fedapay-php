<?php

namespace Tests;

class LogTest extends BaseTestCase
{
    /**
     * Should return array of Fedapay\Log
     */
    public function testShouldReturnLogs()
    {
        $object = \Fedapay\Log::all();

        $this->assertInstanceOf(\Fedapay\FedapayObject::class, $object);
        $this->assertInstanceOf(\Fedapay\FedapayObject::class, $object->meta);
        $this->assertTrue(is_array($object->logs));
        $this->assertInstanceOf(\Fedapay\Log::class, $object->logs[0]);
    }

    /**
     * Should retrieve a Log
     */
    public function testShouldRetrievedALog()
    {
        $object = \Fedapay\Log::all();
        $logs = $object->logs;
        $log = $logs[0];

        $retrieveLog = \Fedapay\Log::retrieve($log->id);

        $this->assertInstanceOf(\Fedapay\Log::class, $retrieveLog);
        $this->assertEquals($retrieveLog->method, $log->method);
        $this->assertEquals($retrieveLog->url, $log->url);
        $this->assertEquals($retrieveLog->status, $log->status);
        $this->assertEquals($retrieveLog->ip_address, $log->ip_address);
        $this->assertEquals($retrieveLog->version, $log->version);
        $this->assertEquals($retrieveLog->source, $log->source);
        $this->assertEquals($retrieveLog->query, $log->query);
        $this->assertEquals($retrieveLog->body, $log->body);
        $this->assertEquals($retrieveLog->response, $log->response);
        $this->assertEquals($retrieveLog->account_id, $log->account_id);
    }
}
