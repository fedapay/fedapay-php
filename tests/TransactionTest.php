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

        $this->mockRequest('get', '/v1/transactions', [], $body);

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
    public function testShouldCreateATransaction()
    {
        $data = [
            'customer' => ['id' => 1],
            'currency' => ['iso' => 'XOF'],
            'description' => 'Description',
            'callback_url' => 'http://localhost/callback',
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

        $this->mockRequest('post', '/v1/transactions', $data, $body);

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
     * Should return array of FedaPay\Transaction
     */
    public function testShouldCreateTransactionInBatch()
    {
        $data = [
            'transactions' => [
                'customer' => ['id' => 1],
                'currency' => ['iso' => 'XOF'],
                'description' => 'Description',
                'callback_url' => 'http://localhost/callback',
                'amount' => 1000
            ],
            'include' => 'customer,currency'
        ];

        $body = [
            'v1/transaction_batch' => [
                'klass' => 'v1/transaction_batch',
                'transactions' => [
                    [
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
                ],
                'errors' => []
            ]
        ];

        $this->mockRequest('post', '/v1/transactions/batch', $data, $body);

        $object = \FedaPay\Transaction::createInBatch($data);

        $this->assertEquals(1, $object->transactions[0]->id);
        $this->assertEquals('0KJAU01', $object->transactions[0]->transaction_key);
        $this->assertEquals('109329828', $object->transactions[0]->reference);
        $this->assertEquals(100, $object->transactions[0]->amount);
        $this->assertEquals('Description', $object->transactions[0]->description);
        $this->assertEquals('http://e-shop.com', $object->transactions[0]->callback_url);
        $this->assertEquals('pending', $object->transactions[0]->status);
        $this->assertInstanceOf(\FedaPay\Customer::class, $object->transactions[0]->customer);
        $this->assertEquals(1, $object->transactions[0]->customer->id);
        $this->assertInstanceOf(\FedaPay\Currency::class, $object->transactions[0]->currency);
        $this->assertEquals(1, $object->transactions[0]->currency->id);
        $this->assertEquals(null, $object->transactions[0]->mode);
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

         $this->mockRequest('get', '/v1/transactions/1', [], $body);

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
        $data = [
            'customer' => ['id' => 1],
            'currency' => ['iso' => 'XOF'],
            'description' => 'Description',
            'callback_url' => 'http://localhost/callback',
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

        $this->mockRequest('put', '/v1/transactions/1', $data, $body);

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
    public function testShouldUpdateATransactionWithSave()
    {
        $data = [
            'customer' => ['id' => 1],
            'currency' => ['iso' => 'XOF'],
            'description' => 'Description',
            'callback_url' => 'http://localhost/callback',
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

        $this->mockRequest('post', '/v1/transactions', $data, $body);

        $transaction = \FedaPay\Transaction::create($data);
        $transaction->description = 'Update description';

        $updateData = [
            'klass' => 'v1/transaction',
            'transaction_key' => '0KJAU01',
            'reference' => '109329828',
            'amount' => 100,
            'description' => 'Update description',
            'callback_url' => 'http://e-shop.com',
            'status' => 'pending',
            'customer' => [
                'klass' => 'v1/customer',
            ],
            'currency' => [
                'klass' => 'v1/currency',
                'iso' => 'XOF'
            ],
            'mode' => null,
            'created_at' => '2018-03-12T09:09:03.969Z',
            'updated_at' => '2018-03-12T09:09:03.969Z',
            'paid_at' => '2018-03-12T09:09:03.969Z'
        ];

        $this->mockRequest('put', '/v1/transactions/1', $updateData, $body);
        $transaction->save();
    }

    /**
     * Should delete a transaction
     */
    public function testShouldDeleteATransaction()
    {
        $data = [
            'customer' => ['id' => 1],
            'currency' => ['iso' => 'XOF'],
            'description' => 'Description',
            'callback_url' => 'http://localhost/callback',
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

        $this->mockRequest('post', '/v1/transactions', $data, $body);
        $transaction = \FedaPay\Transaction::create($data);

        $this->mockRequest('delete', '/v1/transactions/1');
        $transaction->delete();
    }

    /**
     * Should update a transaction with save
     */
    public function testShouldGenerateTransactionToken()
    {
        $data = [
            'customer' => ['id' => 1],
            'currency' => ['iso' => 'XOF'],
            'description' => 'Description',
            'callback_url' => 'http://localhost/callback',
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

        $this->mockRequest('post', '/v1/transactions', $data, $body);

        $transaction = \FedaPay\Transaction::create($data);

        $body = [
            'token' => 'PAYEMENT_TOKEN',
            'url' => 'https://process.fedapay.com/PAYEMENT_TOKEN',
        ];

        $this->mockRequest('post', '/v1/transactions/1/token', [], $body);

        $tokenObject = $transaction->generateToken();
        $this->assertInstanceOf(\FedaPay\FedaPayObject::class, $tokenObject);
        $this->assertEquals('PAYEMENT_TOKEN', $tokenObject->token);
        $this->assertEquals('https://process.fedapay.com/PAYEMENT_TOKEN', $tokenObject->url);
    }

    /**
     * Should update a transaction with save
     */
    public function testShouldSendMtnRequestWithToken()
    {
        $data = [
            'customer' => ['id' => 1],
            'currency' => ['iso' => 'XOF'],
            'description' => 'Description',
            'callback_url' => 'http://localhost/callback',
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

        $this->mockRequest('post', '/v1/transactions', $data, $body);

        $transaction = \FedaPay\Transaction::create($data);

        $this->mockRequest('post', '/v1/mtn', ['token' => 'PAYEMENT_TOKEN'], ['message' => 'success']);

        $object = $transaction->sendNowWithToken('mtn', 'PAYEMENT_TOKEN');
        $this->assertInstanceOf(\FedaPay\FedaPayObject::class, $object);

        $this->assertEquals('success', $object->message);
    }

    /**
     * Should send mtn request
     */
    public function testShouldSendMtnRequest()
    {
        $data = [
            'customer' => ['id' => 1],
            'currency' => ['iso' => 'XOF'],
            'description' => 'Description',
            'callback_url' => 'http://localhost/callback',
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

        $this->mockRequest('post', '/v1/transactions', $data, $body);

        $transaction = \FedaPay\Transaction::create($data);

        $body = [
            'token' => 'PAYEMENT_TOKEN',
            'url' => 'https://process.fedapay.com/PAYEMENT_TOKEN',
        ];

        $this->mockMultipleRequests([
            ['method' => 'post', 'path' => '/v1/transactions/1/token', 'params' => [], 'response' => $body],
            ['method' => 'post', 'path' => '/v1/mtn', 'params' => ['token' => 'PAYEMENT_TOKEN'], 'response' => ['message' => 'success']]
        ]);

        $object = $transaction->sendNow('mtn');
        $this->assertInstanceOf(\FedaPay\FedaPayObject::class, $object);

        $this->assertEquals('success', $object->message);
    }

    /**
     * Should send fees request
     */
    public function testShouldSendFeesRequest()
    {
        $data = [
            'customer' => ['id' => 1],
            'currency' => ['iso' => 'XOF'],
            'description' => 'Description',
            'callback_url' => 'http://localhost/callback',
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

        $this->mockRequest('post', '/v1/transactions', $data, $body);

        $transaction = \FedaPay\Transaction::create($data);

        $body = [
            'amount_debited' => 51020,
            'amount_transferred' => 50000,
            'apply_fees_to_merchant' => false,
            'commission' => 0.02,
            'fees' => 1020,
            'fixed_commission' => 0,
            'message' => '{fees} de frais supplémentaires sont appliqués sur votre paiement.',
        ];

        $this->mockRequest('get', '/v1/transactions/fees', ['token' => 'TOKEN', 'mode' => 'mtn'], $body);

        $object = $transaction->getFees('TOKEN', 'mtn');
        $this->assertInstanceOf(\FedaPay\FedaPayObject::class, $object);

        $this->assertEquals(51020, $object->amount_debited);
        $this->assertEquals(50000, $object->amount_transferred);
        $this->assertEquals(false, $object->apply_fees_to_merchant);
    }
}
