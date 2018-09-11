<?php

namespace Tests;

use Faker\Factory;

class TransactionTest extends BaseTestCase
{
    /**
     * Should return array of FedaPay\Transaction
     */
    public function testShouldReturnTransactions()
    {
        $body = [
            'v1/transactions' => [[
                'id' => 1,
                'klass' => 'v1/transaction',
                'transaction_key' => '0KJAU01',
                'reference' => '109329828',
                'amount' => 100,
                'description' => 'Description',
                'callback_url' => 'http://e-shop.com',
                'status' => 'pending',
                'customer_id' => 1,
                'currency_id' => 1,
                'mode' => 'mtn',
                'created_at' => '2018-03-12T09:09:03.969Z',
                'updated_at' => '2018-03-12T09:09:03.969Z',
                'paid_at' => '2018-03-12T09:09:03.969Z'
            ]],
            'meta' => ['page' => 1]
        ];

        $this->mockRequest('get', '/v1/transactions', null, $body);

        $object = \FedaPay\Transaction::all();

        $this->assertInstanceOf(\FedaPay\FedaPayObject::class, $object);
        $this->assertInstanceOf(\FedaPay\FedaPayObject::class, $object->meta);
        $this->assertInstanceOf(\FedaPay\Transaction::class, $object->transactions[0]);
        $this->assertEquals(1, $object->transactions[0]->id);
        $this->assertEquals('0KJAU01', $object->transactions[0]->transaction_key);
        $this->assertEquals('109329828', $object->transactions[0]->reference);
        $this->assertEquals(100, $object->transactions[0]->amount);
        $this->assertEquals('Description', $object->transactions[0]->description);
        $this->assertEquals('http://e-shop.com', $object->transactions[0]->callback_url);
        $this->assertEquals('pending', $object->transactions[0]->status);
        $this->assertEquals(1, $object->transactions[0]->customer_id);
        $this->assertEquals(1, $object->transactions[0]->currency_id);
        $this->assertEquals('mtn', $object->transactions[0]->mode);
    }

    /**
     * Should return array of FedaPay\Transaction
     */
    // public function testTransactionCreationShouldFailed()
    // {
    //     $data = ['firstname' => 'Myfirstname'];

    //     $body = [
    //         'message' => 'Account creation failed',
    //         'errors' => [
    //             'description' => ['description field required'],
    //             'amount' => ['amount field required']
    //         ]
    //     ];

    //     $client = $this->createMockClient(500, $body);
    //     \FedaPay\Requestor::setHttpClient($client);

    //     try {
    //         \FedaPay\Transaction::create(['firstname' => 'Myfirstname']);
    //     } catch (\FedaPay\Error\ApiConnection $e) {
    //         $this->exceptRequest('/v1/transactions', 'POST', null, $data);

    //         $this->assertTrue($e->hasErrors());
    //         $this->assertNotNull($e->getErrorMessage());
    //         $errors = $e->getErrors();
    //         $this->assertArrayHasKey('description', $errors);
    //         $this->assertArrayHasKey('amount', $errors);
    //     }
    // }

    /**
     * Should return array of FedaPay\Transaction
     */
    public function testShouldCreateATransaction()
    {
        $faker = Factory::create();
        $data = [
            'customer' => ['id' => 1],
            'currency' => ['iso' => 'XOF'],
            'description' => 'Description',
            'callback_url' => $faker->url,
            'amount' => 1000,
            'include' => 'customer,currency'
        ];

        $body = [
            'v1/transaction' => [
                'id' => 1,
                'klass' => 'v1/transaction',
                'transaction_key' => '0KJAU01',
                'reference' => '109329828',
                'amount' => 100,
                'description' => 'Description',
                'callback_url' => 'http://e-shop.com',
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
                'paid_at' => '2018-03-12T09:09:03.969Z'
            ]
        ];

        $this->mockRequest('post', '/v1/transactions', null, $body);

        $transaction = \FedaPay\Transaction::create($data);

        $this->assertInstanceOf(\FedaPay\Transaction::class, $transaction);
        $this->assertEquals(1, $transaction->id);
        $this->assertEquals('0KJAU01', $transaction->transaction_key);
        $this->assertEquals('109329828', $transaction->reference);
        $this->assertEquals(100, $transaction->amount);
        $this->assertEquals('Description', $transaction->description);
        $this->assertEquals('http://e-shop.com', $transaction->callback_url);
        $this->assertEquals('pending', $transaction->status);
        $this->assertInstanceOf(\FedaPay\Customer::class, $transaction->customer);
        $this->assertEquals(1, $transaction->customer->id);
        $this->assertInstanceOf(\FedaPay\Currency::class, $transaction->currency);
        $this->assertEquals(1, $transaction->currency->id);
        $this->assertEquals(null, $transaction->mode);
    }

    /**
     * Should retrieve a Transaction
     */
    public function testShouldRetrievedATransaction()
    {
        $body = [
            'v1/transaction' => [
                'id' => 1,
                'klass' => 'v1/transaction',
                'transaction_key' => '0KJAU01',
                'reference' => '109329828',
                'amount' => 100,
                'description' => 'Description',
                'callback_url' => 'http://e-shop.com',
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
                'paid_at' => '2018-03-12T09:09:03.969Z'
            ]
        ];

         $this->mockRequest('get', '/v1/transactions/1', null, $body);

        $transaction = \FedaPay\Transaction::retrieve(1);

        $this->assertInstanceOf(\FedaPay\Transaction::class, $transaction);
        $this->assertEquals(1, $transaction->id);
        $this->assertEquals('0KJAU01', $transaction->transaction_key);
        $this->assertEquals('109329828', $transaction->reference);
        $this->assertEquals(100, $transaction->amount);
        $this->assertEquals('Description', $transaction->description);
        $this->assertEquals('http://e-shop.com', $transaction->callback_url);
        $this->assertEquals('pending', $transaction->status);
        $this->assertInstanceOf(\FedaPay\Customer::class, $transaction->customer);
        $this->assertEquals(1, $transaction->customer->id);
        $this->assertInstanceOf(\FedaPay\Currency::class, $transaction->currency);
        $this->assertEquals(1, $transaction->currency->id);
        $this->assertEquals(null, $transaction->mode);
    }

    /**
     * Should update a transaction
     */
    public function testShouldUpdateATransaction()
    {
        $faker = Factory::create();
        $data = [
            'customer' => ['id' => 1],
            'currency' => ['iso' => 'XOF'],
            'description' => 'Description',
            'callback_url' => $faker->url,
            'amount' => 1000,
            'include' => 'customer,currency'
        ];
        $body = [
            'v1/transaction' => [
                'id' => 1,
                'klass' => 'v1/transaction',
                'transaction_key' => '0KJAU01',
                'reference' => '109329828',
                'amount' => 100,
                'description' => 'Description',
                'callback_url' => 'http://e-shop.com',
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
                'paid_at' => '2018-03-12T09:09:03.969Z'
            ]
        ];

        $this->mockRequest('put', '/v1/transactions', null, $body);

        $transaction = \FedaPay\Transaction::update(1, $data);

        $this->assertInstanceOf(\FedaPay\Transaction::class, $transaction);
        $this->assertEquals(1, $transaction->id);
        $this->assertEquals('0KJAU01', $transaction->transaction_key);
        $this->assertEquals('109329828', $transaction->reference);
        $this->assertEquals(100, $transaction->amount);
        $this->assertEquals('Description', $transaction->description);
        $this->assertEquals('http://e-shop.com', $transaction->callback_url);
        $this->assertEquals('pending', $transaction->status);
        $this->assertInstanceOf(\FedaPay\Customer::class, $transaction->customer);
        $this->assertEquals(1, $transaction->customer->id);
        $this->assertInstanceOf(\FedaPay\Currency::class, $transaction->currency);
        $this->assertEquals(1, $transaction->currency->id);
        $this->assertEquals(null, $transaction->mode);
    }

    /**
     * Should update a transaction with save
     */
    // public function testShouldUpdateATransactionWithSave()
    // {
    //     $faker = Factory::create();
    //     $data = [
    //         'customer' => ['id' => 1],
    //         'currency' => ['iso' => 'XOF'],
    //         'description' => 'Description',
    //         'callback_url' => $faker->url,
    //         'amount' => 1000,
    //         'include' => 'customer,currency'
    //     ];

    //     $body = [
    //         'v1/transaction' => [
    //             'id' => 1,
    //             'klass' => 'v1/transaction',
    //             'transaction_key' => '0KJAU01',
    //             'reference' => '109329828',
    //             'amount' => 100,
    //             'description' => 'Description',
    //             'callback_url' => 'http://e-shop.com',
    //             'status' => 'pending',
    //             'customer' => [
    //                 'id' => 1,
    //                 'klass' => 'v1/customer',
    //             ],
    //             'currency' => [
    //                 'id' => 1,
    //                 'klass' => 'v1/currency',
    //                 'iso' => 'XOF'
    //             ],
    //             'mode' => null,
    //             'created_at' => '2018-03-12T09:09:03.969Z',
    //             'updated_at' => '2018-03-12T09:09:03.969Z',
    //             'paid_at' => '2018-03-12T09:09:03.969Z'
    //         ]
    //     ];

    //     $client = $this->createMockClient(200, $body);
    //     \FedaPay\Requestor::setHttpClient($client);

    //     $transaction = \FedaPay\Transaction::create($data);

    //     $transaction->description = 'Update description';

    //     $client = $this->createMockClient(200, $body);
    //     \FedaPay\Requestor::setHttpClient($client);

    //     $transaction->save();

    //     $this->exceptRequest('/v1/transactions/1', 'PUT', null, [
    //         'description' => 'Update description'
    //     ]);
    // }

    /**
     * Should delete a transaction
     */
    public function testShouldDeleteATransaction()
    {
        $faker = Factory::create();
        $data = [
            'customer' => ['id' => 1],
            'currency' => ['iso' => 'XOF'],
            'description' => 'Description',
            'callback_url' => $faker->url,
            'amount' => 1000,
            'include' => 'customer,currency'
        ];

        $body = [
            'v1/transaction' => [
                'id' => 1,
                'klass' => 'v1/transaction',
                'transaction_key' => '0KJAU01',
                'reference' => '109329828',
                'amount' => 100,
                'description' => 'Description',
                'callback_url' => 'http://e-shop.com',
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
                'paid_at' => '2018-03-12T09:09:03.969Z'
            ]
        ];

        //$this->mockRequest('post', '/v1/transactions', null, $body);

        //$transaction = \FedaPay\Transaction::create($data);
        //$transaction->delete();

    }

    /**
     * Should update a transaction with save
     */
    // public function testShouldGenerateTransactionToken()
    // {
    //     $faker = Factory::create();
    //     $data = [
    //         'customer' => ['id' => 1],
    //         'currency' => ['iso' => 'XOF'],
    //         'description' => 'Description',
    //         'callback_url' => $faker->url,
    //         'amount' => 1000,
    //         'include' => 'customer,currency'
    //     ];

    //     $body = [
    //         'v1/transaction' => [
    //             'id' => 1,
    //             'klass' => 'v1/transaction',
    //             'transaction_key' => '0KJAU01',
    //             'reference' => '109329828',
    //             'amount' => 100,
    //             'description' => 'Description',
    //             'callback_url' => 'http://e-shop.com',
    //             'status' => 'pending',
    //             'customer' => [
    //                 'id' => 1,
    //                 'klass' => 'v1/customer',
    //             ],
    //             'currency' => [
    //                 'id' => 1,
    //                 'klass' => 'v1/currency',
    //                 'iso' => 'XOF'
    //             ],
    //             'mode' => null,
    //             'created_at' => '2018-03-12T09:09:03.969Z',
    //             'updated_at' => '2018-03-12T09:09:03.969Z',
    //             'paid_at' => '2018-03-12T09:09:03.969Z'
    //         ]
    //     ];

    //     $this->mockRequest('post', '/v1/transactions', null, $body);

    //     $transaction = \FedaPay\Transaction::create($data);

    //     $body = [
    //         'token' => 'PAYEMENT_TOKEN',
    //         'url' => 'https://process.fedapay.com/PAYEMENT_TOKEN',
    //     ];

    //     $this->mockRequest('post', '/v1/transactions/1/token', null, $body);

    //     $tokenObject = $transaction->generateToken();

    //     $this->assertInstanceOf(\FedaPay\FedaPayObject::class, $tokenObject);
    //     $this->assertEquals('PAYEMENT_TOKEN', $tokenObject->token);
    //     $this->assertEquals('https://process.fedapay.com/PAYEMENT_TOKEN', $tokenObject->url);
    // }
}
