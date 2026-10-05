<?php

namespace Tests;

class SubLedgerBalanceTest extends BaseTestCase
{
    /**
     * Should return FedaPay\SubLedgerBalance
     */
    public function testShouldReturnSubLedgerBalances()
    {
        $body = [
            'merchant_id' => 'merchant_1000',
            'known' => true,
            'balances' => [
                'XOF' => [
                    'available' => 2500,
                    'pending' => 0,
                    'rolling_reserve' => 0,
                    'chargeback_hold' => 0,
                    'payment_request_hold' => 0,
                    'refund_hold' => 0,
                    'payout_hold' => 0,
                    'open_daily_balance' => 0
                ]
            ]
        ];

        $this->mockRequest('get', '/v1/sub_ledger_balances', [], $body);

        $object = \FedaPay\SubLedgerBalance::all();

        $this->assertInstanceOf(\FedaPay\FedaPayObject::class, $object);
        $this->assertEquals('merchant_1000', $object->merchant_id);
        $this->assertEquals(true, $object->known);
        $this->assertInstanceOf(\FedaPay\FedaPayObject::class, $object->balances);
        $this->assertInstanceOf(\FedaPay\FedaPayObject::class, $object->balances->XOF);
    }
}
