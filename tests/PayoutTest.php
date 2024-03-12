<?php

namespace Tests;

use Faker\Factory;

class PayoutTest extends BaseTestCase
{
    private function createPayout()
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
                'amount' => 1000,
                'status' => 'pending',
                'customer' => [
                    'id' => 1,
                    'klass' => 'v1/customer',
                ],
                'balance_id' => 1,
                'mode' => 'mtn',
                'created_at' => '2018-03-12T09:09:03.969Z',
                'updated_at' => '2018-03-12T09:09:03.969Z'
            ]
        ];

        $this->mockRequest('post', '/v1/payouts', $data, $body);

        return \FedaPay\Payout::create($data);
    }

    /**
     * Should return array of FedaPay\Payout
     */
    public function testShouldReturnPayouts()
    {
        $body = [
            'v1/payouts' => [
                [
                    'klass' => 'v1/payout',
                    'id' => 1,
                    'reference' => '1539796844261',
                    'amount' => 1000,
                    'status' => 'pending',
                    'customer_id' => 1,
                    'mode' => 'mtn',
                    'last_error_code' => null,
                    'last_error_message' => null,
                    'created_at' => '2018-10-17T17:20:44.261Z',
                    'updated_at' => '2018-10-17T17:22:47.816Z',
                    'currency_id' => 1,
                    'scheduled_at' => '2018-10-17T17:22:06.626Z',
                    'sent_at' => '2018-10-17T17:22:47.803Z',
                    'failed_at' => null,
                    'deleted_at' => null
                ]
            ],
            'meta' => [
                "current_page" => 1,
                "next_page" => null,
                "prev_page" => null,
                "total_pages" => 1,
                "total_count" => 11,
                "per_page" => 25
            ]
        ];

        $this->mockRequest('get', '/v1/payouts', [], $body);

        $object = \FedaPay\Payout::all();

        $this->assertInstanceOf(\FedaPay\FedaPayObject::class, $object);
        $this->assertInstanceOf(\FedaPay\FedaPayObject::class, $object->meta);
        $this->assertInstanceOf(\FedaPay\Payout::class, $object->payouts[0]);
        $this->assertEquals(1, $object->payouts[0]->id);
        $this->assertEquals('1539796844261', $object->payouts[0]->reference);
        $this->assertEquals(1000, $object->payouts[0]->amount);
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
            'scheduled_at' => '2018-03-12T09:09:03.969Z',
            'include' => 'customer,currency'
        ];

        $body = [
            'v1/payout' => [
                'amount' => 1000,
                'currency_id' => 1,
                'created_at' => '2018-10-23T15:21:50.434Z',
                'customer_id' => 1,
                'deleted_at' => null,
                'failed_at' => null,
                'id' => 13,
                'klass' => 'v1/payout',
                'last_error_code' => null,
                'last_error_message' => null,
                'mode' => 'mtn',
                'reference' => '1540308110435',
                'scheduled_at' => '2018-11-12T09:09:03.969Z',
                'sent_at' => null,
                'status' => 'pending',
                'updated_at' => '2018-10-23T15:21:50.434Z',
                'customer' => [
                    'klass' => 'v1/customer',
                    'id' => 1,
                    'firstname' => 'SOHOU',
                    'lastname' => 'Zidial',
                    'email' => 'zinsou@test.com',
                    'account_id' => 1,
                    'created_at' => '2018-10-17T16:03:24.061Z',
                    'updated_at' => '2018-10-17T16:03:24.061Z'
                ]
            ]
        ];

        $this->mockRequest('post', '/v1/payouts', $data, $body);

        $payout = \FedaPay\Payout::create($data);

        $this->assertInstanceOf(\FedaPay\Payout::class, $payout);
        $this->assertEquals(13, $payout->id);
        $this->assertEquals('1540308110435', $payout->reference);
        $this->assertEquals(1000, $payout->amount);
        $this->assertEquals('pending', $payout->status);
        $this->assertInstanceOf(\FedaPay\Customer::class, $payout->customer);
        $this->assertEquals(1, $payout->customer->id);
        $this->assertEquals('mtn', $payout->mode);
    }

    /**
     * Should return array of FedaPay\Payout
     */
    public function testShouldCreatePayoutInBatch()
    {
        $data = [
            'payouts' => [
                'customer' => ['id' => 1],
                'currency' => ['iso' => 'XOF'],
                'amount' => 1000,
                'mode' => 'mtn',
                'scheduled_at' => '2018-03-12T09:09:03.969Z'
            ],
            'include' => 'customer,currency'
        ];

        $body = [
            'v1/payout_batch' => [
                'klass' => 'v1/payout_batch',
                'payouts' => [
                    [
                        'amount' => 1000,
                        'currency_id' => 1,
                        'created_at' => '2018-10-23T15:21:50.434Z',
                        'customer_id' => 1,
                        'deleted_at' => null,
                        'failed_at' => null,
                        'id' => 13,
                        'klass' => 'v1/payout',
                        'last_error_code' => null,
                        'last_error_message' => null,
                        'mode' => 'mtn',
                        'reference' => '1540308110435',
                        'scheduled_at' => '2018-11-12T09:09:03.969Z',
                        'sent_at' => null,
                        'status' => 'pending',
                        'updated_at' => '2018-10-23T15:21:50.434Z',
                        'customer' => [
                            'klass' => 'v1/customer',
                            'id' => 1,
                            'firstname' => 'SOHOU',
                            'lastname' => 'Zidial',
                            'email' => 'zinsou@test.com',
                            'account_id' => 1,
                            'created_at' => '2018-10-17T16:03:24.061Z',
                            'updated_at' => '2018-10-17T16:03:24.061Z'
                        ]
                    ]
                ],
                'errors' => []
            ]
        ];

        $this->mockRequest('post', '/v1/payouts/batch', $data, $body);

        $object = \FedaPay\Payout::createInBatch($data);

        $this->assertInstanceOf(\FedaPay\FedaPayObject::class, $object);
        $this->assertEquals(13, $object->payouts[0]->id);
        $this->assertEquals('1540308110435', $object->payouts[0]->reference);
        $this->assertEquals(1000, $object->payouts[0]->amount);
        $this->assertEquals('pending', $object->payouts[0]->status);
        $this->assertInstanceOf(\FedaPay\Customer::class, $object->payouts[0]->customer);
        $this->assertEquals(1, $object->payouts[0]->customer->id);
        $this->assertEquals('mtn', $object->payouts[0]->mode);
    }

    /**
     * Should retrieve a Payout
     */
    public function testShouldRetrievedAPayout()
    {
        $body = [
            'v1/payout' => [
                'amount' => 1000,
                'currency_id' => 1,
                'created_at' => '2018-10-23T15:21:50.434Z',
                'customer_id' => 1,
                'deleted_at' => null,
                'failed_at' => null,
                'id' => 13,
                'klass' => 'v1/payout',
                'last_error_code' => null,
                'last_error_message' => null,
                'mode' => 'mtn',
                'reference' => '1540308110435',
                'scheduled_at' => '2018-11-12T09:09:03.969Z',
                'sent_at' => null,
                'status' => 'pending',
                'updated_at' => '2018-10-23T15:21:50.434Z'
            ]
        ];

        $this->mockRequest('get', '/v1/payouts/13', [], $body);

        $payout = \FedaPay\Payout::retrieve(13);

        $this->assertInstanceOf(\FedaPay\Payout::class, $payout);
        $this->assertEquals(13, $payout->id);
        $this->assertEquals('1540308110435', $payout->reference);
        $this->assertEquals(1000, $payout->amount);
        $this->assertEquals('pending', $payout->status);
        $this->assertEquals('mtn', $payout->mode);
    }

    /**
     * Should start a Payout
     */
    public function testShouldScheduleAPayout()
    {
        $payout = $this->createPayout();

        $body = [
            'v1/payouts' => [
                [
                    'klass' => 'v1/payout',
                    'id' => 1,
                    'reference' => '1540316134325',
                    'amount' => 1000,
                    'status' => 'started',
                    'customer_id' => 1,
                    'currency_id' => 1,
                    'mode' => 'mtn',
                    'last_error_code' => null,
                    'last_error_message' => null,
                    'created_at' => '2018-10-23T17:35:34.325Z',
                    'updated_at' => '2018-10-23T17:36:40.086Z',
                    'scheduled_at' => '2018-11-01 18:30:22',
                    'sent_at' => null,
                    'started_at' => '2018-11-01 18:30:22',
                    'failed_at' => null,
                    'deleted_at' => null
                ]
            ]
        ];

        $data = [
            'payouts' => [[
                'id' => 1,
                'scheduled_at' => '2018-11-01 18:30:22'
            ]]
        ];

        $this->mockRequest('put', '/v1/payouts/start', $data, $body);

        $payout->schedule('2018-11-01 18:30:22');

        $this->assertEquals('2018-11-01 18:30:22', $payout->scheduled_at);
        $this->assertEquals('2018-11-01 18:30:22', $payout->started_at);
        $this->assertEquals('started', $payout->status);
    }

    /**
     * Should fail schedule all payouts
     */
    public function testShouldFailScheduleAllPayouts()
    {
        $data = [[
            'scheduled_at' => '2018-11-01 18:30:22'
        ]];

        $this->expectException('\InvalidArgumentException');
        $this->expectExceptionMessage('Invalid id argument. You must specify payout id.');

        \FedaPay\Payout::scheduleAll($data);
    }

    /**
     * Should schedule all Payouts
     */
    public function testShouldScheduleAllPayouts()
    {
        $body = [
            'v1/payouts' => [
                [
                    'klass' => 'v1/payout',
                    'id' => 1,
                    'reference' => '1540316134325',
                    'amount' => 1000,
                    'status' => 'started',
                    'customer_id' => 1,
                    'currency_id' => 1,
                    'mode' => 'mtn',
                    'last_error_code' => null,
                    'last_error_message' => null,
                    'created_at' => '2018-10-23T17:35:34.325Z',
                    'updated_at' => '2018-10-23T17:36:40.086Z',
                    'scheduled_at' => '2018-11-01 18:30:22',
                    'sent_at' => null,
                    'started_at' => '2018-11-01 18:30:22',
                    'failed_at' => null,
                    'deleted_at' => null
                ]
            ]
        ];

        $data = [[
            'id' => 1,
            'scheduled_at' => '2018-11-01 18:30:22'
        ]];

        $this->mockRequest('put', '/v1/payouts/start', ['payouts' => $data], $body);

        $object = \FedaPay\Payout::scheduleAll($data);

        $this->assertInstanceOf(\FedaPay\FedaPayObject::class, $object);
        $this->assertInstanceOf(\FedaPay\Payout::class, $object->payouts[0]);
        $this->assertEquals(1, $object->payouts[0]->id);
        $this->assertEquals('1540316134325', $object->payouts[0]->reference);
        $this->assertEquals(1000, $object->payouts[0]->amount);
        $this->assertEquals('started', $object->payouts[0]->status);
        $this->assertEquals('mtn', $object->payouts[0]->mode);
    }

    /**
     * Should send a Payout now
     */
    public function testShouldSendAPayoutNow()
    {
        $payout = $this->createPayout();

        $body = [
            'v1/payouts' => [
                [
                    'klass' => 'v1/payout',
                    'id' => 1,
                    'reference' => '1540316134325',
                    'amount' => 1000,
                    'status' => 'sent',
                    'customer_id' => 1,
                    'currency_id' => 1,
                    'mode' => 'mtn',
                    'last_error_code' => null,
                    'last_error_message' => null,
                    'created_at' => '2018-10-23T17:35:34.325Z',
                    'updated_at' => '2018-10-23T17:36:40.086Z',
                    'scheduled_at' => '2018-11-01 18:30:22',
                    'sent_at' => '2018-11-01 18:30:22',
                    'started_at' => '2018-11-01 18:30:22',
                    'failed_at' => null,
                    'deleted_at' => null
                ]
            ]
        ];

        $data = [
            'payouts' => [[
                'id' => 1
            ]]
        ];

        $this->mockRequest('put', '/v1/payouts/start', $data, $body);

        $payout->sendNow();

        $this->assertEquals('2018-11-01 18:30:22', $payout->sent_at);
        $this->assertEquals('sent', $payout->status);
    }


    /**
     * Should send all payouts now
     */
    public function testShouldSendAllPayoutsNow()
    {
        $body = [
            'v1/payouts' => [
                [
                    'klass' => 'v1/payout',
                    'id' => 1,
                    'reference' => '1540316134325',
                    'amount' => 1000,
                    'status' => 'sent',
                    'customer_id' => 1,
                    'currency_id' => 1,
                    'mode' => 'mtn',
                    'last_error_code' => null,
                    'last_error_message' => null,
                    'created_at' => '2018-10-23T17:35:34.325Z',
                    'updated_at' => '2018-10-23T17:36:40.086Z',
                    'scheduled_at' => '2018-11-01 18:30:22',
                    'sent_at' => null,
                    'started_at' => '2018-11-01 18:30:22',
                    'failed_at' => null,
                    'deleted_at' => null
                ]
            ]
        ];

        $data = [[
            'id' => 1
        ]];

        $this->mockRequest('put', '/v1/payouts/start', ['payouts' => $data], $body);

        $object = \FedaPay\Payout::sendAllNow([['id' => 1]]);

        $this->assertInstanceOf(\FedaPay\FedaPayObject::class, $object);
        $this->assertInstanceOf(\FedaPay\Payout::class, $object->payouts[0]);
        $this->assertEquals(1, $object->payouts[0]->id);
        $this->assertEquals('1540316134325', $object->payouts[0]->reference);
        $this->assertEquals(1000, $object->payouts[0]->amount);
        $this->assertEquals('sent', $object->payouts[0]->status);
        $this->assertEquals('mtn', $object->payouts[0]->mode);
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
                'amount' => 1000,
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
        $this->assertEquals(1000, $payout->amount);
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
                'amount' => 1000,
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
                'amount' => 1000,
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

        $this->mockRequest('delete', '/v1/payouts/1');
        $payout->delete();
    }
}
