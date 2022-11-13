<?php

namespace Tests;

class BalanceTest extends BaseTestCase
{
    /**
     * Should return FedaPay\Balance
     */
    public function testShouldReturnCurrencies()
    {
        $body = [
            'v1/balances' => [[
                'id' => 1,
                'klass' => 'v1/balance',
                'currency_id' => 1,
                'account_id' => 1,
                'amount' => 952,
                'mode' => 'mtn',
                'created_at' => '2018-03-12T09:09:03.969Z',
                'updated_at' => '2018-03-12T09:09:03.969Z'
            ]]
        ];

        $this->mockRequest('get', '/v1/balances', [], $body);

        $object = \FedaPay\Balance::all();

        $this->assertInstanceOf(\FedaPay\FedaPayObject::class, $object);
        $this->assertTrue(is_array($object->balances));
        $this->assertInstanceOf(\FedaPay\Balance::class, $object->balances[0]);
        $this->assertEquals(1, $object->balances[0]->currency_id);
        $this->assertEquals(1, $object->balances[0]->account_id);
        $this->assertEquals(952, $object->balances[0]->amount);
        $this->assertEquals('mtn', $object->balances[0]->mode);
    }

    /**
     * Should retrieve a Customer
     */
    public function testShouldRetrievedABalance()
    {
        $body = [
            'v1/balance' => [
                'id' => 1,
                'klass' => 'v1/balance',
                'currency_id' => 1,
                'account_id' => 1,
                'amount' => 952,
                'mode' => 'mtn',
                'created_at' => '2018-03-12T09:09:03.969Z',
                'updated_at' => '2018-03-12T09:09:03.969Z'
            ]
        ];

        $this->mockRequest('get', '/v1/balances/1', [], $body);

        $balance = \FedaPay\Balance::retrieve(1);

        $this->assertInstanceOf(\FedaPay\Balance::class, $balance);
        $this->assertEquals(1, $balance->currency_id);
        $this->assertEquals(1, $balance->account_id);
        $this->assertEquals(952, $balance->amount);
        $this->assertEquals('mtn', $balance->mode);
    }
}
