<?php

namespace Tests;

use Faker\Factory;

class PayoutTest extends BaseTestCase
{
    /**
     * Should return array of FedaPay\Payout
     */
    public function testShouldReturnPayouts()
    {
        $body = [
            'v1/payouts' => [[
                'id' => 1,
                'klass' => 'v1/payout',
                'reference' => '109329828',
                'amount' => 100,
                'status' => 'pending',
                'customer_id' => 1,
                'currency_id' => 1,
                'mode' => 'mtn',
                'last_error_code' => null,
                'last_error_message' => null,
                'created_at' => '2018-03-12T09:09:03.969Z',
                'updated_at' => '2018-03-12T09:09:03.969Z',
                'scheduled_at' => '2018-03-12T09:09:03.969Z',
                'sent_at' => '2018-03-12T09:09:03.969Z',
                'failed_at' => '2018-03-12T09:09:03.969Z',
                'deleted_at' => '2018-03-12T09:09:03.969Z'
            ]],
            'meta' => ['page' => 1]
        ];

        $this->mockRequest('get', '/v1/payouts', [], $body);

        $object = \FedaPay\Payout::all();

        $this->assertInstanceOf(\FedaPay\FedaPayObject::class, $object);
        $this->assertInstanceOf(\FedaPay\FedaPayObject::class, $object->meta);
        $this->assertInstanceOf(\FedaPay\Payout::class, $object->payouts[0]);
        $this->assertEquals(1, $object->payouts[0]->id);
        $this->assertEquals('109329828', $object->payouts[0]->reference);
        $this->assertEquals(100, $object->payouts[0]->amount);
        $this->assertEquals('pending', $object->payouts[0]->status);
        $this->assertEquals(1, $object->payouts[0]->customer_id);
        $this->assertEquals(1, $object->payouts[0]->currency_id);
        $this->assertEquals('mtn', $object->payouts[0]->mode);
    }

    /**
     * Should return array of FedaPay\Payout
     */
    public function testShouldCreateAPayout()
    {
        $data = [
            'customer' => ['id' => 1],
            'currency' => ['iso' => 'XOF'],
            'amount' => 1000,
            'mode' => 'mtn',
            'scheduled_at' => '2018-03-12T09:09:03.969Z'
        ];

        $body = [
            'v1/payout' => [
                'id' => 1,
                'klass' => 'v1/payout',
                'reference' => '109329828',
                'amount' => 100,
                'status' => 'pending',
                'customer' => [
                    'id' => 1,
                    'klass' => 'v1/customer',
                ],
                'currency' => [
                    'id' => 1,
                    'klass' => 'v1/currency',
                    'iso' => 'XOF'
                ],
                'mode' => 'mtn',
                'created_at' => '2018-03-12T09:09:03.969Z',
                'updated_at' => '2018-03-12T09:09:03.969Z',
            ]
        ];

        $this->mockRequest('post', '/v1/payouts', $data, $body);

        $payout = \FedaPay\Payout::create($data);

        $this->assertInstanceOf(\FedaPay\Payout::class, $payout);
        $this->assertEquals(1, $payout->id);
        $this->assertEquals('109329828', $payout->reference);
        $this->assertEquals(100, $payout->amount);
        $this->assertEquals('pending', $payout->status);
        $this->assertInstanceOf(\FedaPay\Customer::class, $payout->customer);
        $this->assertEquals(1, $payout->customer->id);
        $this->assertInstanceOf(\FedaPay\Currency::class, $payout->currency);
        $this->assertEquals(1, $payout->currency->id);
        $this->assertEquals('mtn', $payout->mode);
    }

    /**
     * Should retrieve a Payout
     */
    public function testShouldRetrievedAPayout()
    {
        $body = [
            'v1/payout' => [
                'id' => 1,
                'klass' => 'v1/payout',
                'reference' => '109329828',
                'amount' => 100,
                'status' => 'pending',
                'customer' => [
                    'id' => 1,
                    'klass' => 'v1/customer',
                ],
                'currency' => [
                    'id' => 1,
                    'klass' => 'v1/currency',
                    'iso' => 'XOF'
                ],
                'mode' => 'mtn',
                'created_at' => '2018-03-12T09:09:03.969Z',
                'updated_at' => '2018-03-12T09:09:03.969Z',
            ]
        ];

        $this->mockRequest('get', '/v1/payouts/1', [], $body);

        $payout = \FedaPay\Payout::retrieve(1);

        $this->assertInstanceOf(\FedaPay\Payout::class, $payout);
        $this->assertEquals(1, $payout->id);
        $this->assertEquals('109329828', $payout->reference);
        $this->assertEquals(100, $payout->amount);
        $this->assertEquals('pending', $payout->status);
        $this->assertInstanceOf(\FedaPay\Customer::class, $payout->customer);
        $this->assertEquals(1, $payout->customer->id);
        $this->assertInstanceOf(\FedaPay\Currency::class, $payout->currency);
        $this->assertEquals(1, $payout->currency->id);
        $this->assertEquals('mtn', $payout->mode);
    }

    /**
     * Should update a payout
     */
    public function testShouldUpdateAPayout()
    {
        $data = [
            'customer' => ['id' => 1],
            'currency' => ['iso' => 'XOF'],
            'amount' => 1000
        ];
        $body = [
            'v1/payout' => [
                'id' => 1,
                'klass' => 'v1/payout',
                'reference' => '109329828',
                'amount' => 100,
                'status' => 'pending',
                'customer' => [
                    'id' => 1,
                    'klass' => 'v1/customer',
                ],
                'currency' => [
                    'id' => 1,
                    'klass' => 'v1/currency',
                    'iso' => 'XOF'
                ],
                'mode' => null,
                'created_at' => '2018-03-12T09:09:03.969Z',
                'updated_at' => '2018-03-12T09:09:03.969Z',
            ]
        ];

        $this->mockRequest('put', '/v1/payouts/1', $data, $body);

        $payout = \FedaPay\Payout::update(1, $data);

        $this->assertInstanceOf(\FedaPay\Payout::class, $payout);
        $this->assertEquals(1, $payout->id);
        $this->assertEquals('109329828', $payout->reference);
        $this->assertEquals(100, $payout->amount);
        $this->assertEquals('pending', $payout->status);
        $this->assertInstanceOf(\FedaPay\Customer::class, $payout->customer);
        $this->assertEquals(1, $payout->customer->id);
        $this->assertInstanceOf(\FedaPay\Currency::class, $payout->currency);
        $this->assertEquals(1, $payout->currency->id);
        $this->assertEquals(null, $payout->mode);
    }

    /**
     * Should update a payout with save
     */
    public function testShouldUpdateAPayoutWithSave()
    {
        $data = [
            'customer' => ['id' => 1],
            'currency' => ['iso' => 'XOF'],
            'amount' => 1000,
        ];

        $body = [
            'v1/payout' => [
                'id' => 1,
                'klass' => 'v1/payout',
                'reference' => '109329828',
                'amount' => 100,
                'status' => 'pending',
                'customer' => [
                    'id' => 1,
                    'klass' => 'v1/customer',
                ],
                'currency' => [
                    'id' => 1,
                    'klass' => 'v1/currency',
                    'iso' => 'XOF'
                ],
                'mode' => 'mtn',
                'created_at' => '2018-03-12T09:09:03.969Z',
                'updated_at' => '2018-03-12T09:09:03.969Z',
            ]
        ];

        $this->mockRequest('post', '/v1/payouts', $data, $body);

        $payout = \FedaPay\Payout::create($data);
        $payout->amount = 5000;

        $updateData = [
            'klass' => 'v1/payout',
            'reference' => '109329828',
            'amount' => 5000,
            'status' => 'pending',
            'customer' => [
                'klass' => 'v1/customer',
            ],
            'currency' => [
                'klass' => 'v1/currency',
                'iso' => 'XOF'
            ],
            'mode' => 'mtn',
            'created_at' => '2018-03-12T09:09:03.969Z',
            'updated_at' => '2018-03-12T09:09:03.969Z',
        ];

        $this->mockRequest('put', '/v1/payouts/1', $updateData, $body);
        $payout->save();
    }

    /**
     * Should delete a payout
     */
    public function testShouldDeleteAPayout()
    {
        $data = [
            'customer' => ['id' => 1],
            'currency' => ['iso' => 'XOF'],
            'amount' => 1000,
        ];

        $body = [
            'v1/payout' => [
                'id' => 1,
                'klass' => 'v1/payout',
                'reference' => '109329828',
                'amount' => 100,
                'status' => 'pending',
                'customer' => [
                    'id' => 1,
                    'klass' => 'v1/customer',
                ],
                'currency' => [
                    'id' => 1,
                    'klass' => 'v1/currency',
                    'iso' => 'XOF'
                ],
                'mode' => null,
                'created_at' => '2018-03-12T09:09:03.969Z',
                'updated_at' => '2018-03-12T09:09:03.969Z',
            ]
        ];

        $this->mockRequest('post', '/v1/payouts', $data, $body);
        $payout = \FedaPay\Payout::create($data);

        $this->mockRequest('delete', '/v1/payouts/1');
        $payout->delete();
    }
}
