<?php

namespace Tests;

class PaymentRequestTest extends BaseTestCase
{
    /**
     * Should return array of FedaPay\PaymentRequest
     */
    public function testShouldReturnPaymentRequests()
    {
        $body = [
            'v1/payment_requests' => [
                [
                    'klass' => 'v1/payment_request',
                    'id' => 1,
                    'balance_amount_before' => 5487905,
                    'balance_amount_after' => 5486905,
                    'status' => 'canceled',
                    'amount' => 1000,
                    'mode' => 'mtn',
                    'receipt_info' => 'Just a test',
                    'commission' => '0.0',
                    'fixed_commission' => 0,
                    'fees' => 0,
                    'amount_transferred' => 1000,
                    'sourceable_id' => 1672,
                    'sourceable_type' => 'V1::MobileAccount',
                    'approved_at' => null,
                    'transferred_at' => null,
                    'to_transfer' => false,
                    'declined_at' => null,
                    'canceled_at' => '2026-04-27T11:43:08.654+01:00',
                    'created_at' => '2025-05-22T15:57:35.656+01:00',
                    'updated_at' => '2026-04-27T11:43:08.685+01:00',
                    'freezed_at' => null,
                    'approved_user_id' => null,
                    'transferred_user_id' => null,
                    'metadata' => [],
                    'user_id' => 200,
                    'balance_id' => 19796,
                    'reference' => 'pr_WNk_1539796844261',
                    'transaction_key' => null,
                    'declined_reason' => null,
                    'flags' => null,
                    'created_via' => 'manual'
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

        $this->mockRequest('get', '/v1/payment_requests', [], $body);

        $object = \FedaPay\PaymentRequest::all();

        $this->assertInstanceOf(\FedaPay\FedaPayObject::class, $object);
        $this->assertInstanceOf(\FedaPay\FedaPayObject::class, $object->meta);
        $this->assertInstanceOf(\FedaPay\PaymentRequest::class, $object->payment_requests[0]);
        $this->assertEquals(1, $object->payment_requests[0]->id);
        $this->assertEquals('pr_WNk_1539796844261', $object->payment_requests[0]->reference);
        $this->assertEquals(1000, $object->payment_requests[0]->amount);
        $this->assertEquals('canceled', $object->payment_requests[0]->status);
        $this->assertEquals('mtn', $object->payment_requests[0]->mode);
    }

    /**
     * Should return array of FedaPay\PaymentRequest
     */
    public function testShouldCreateAPaymentRequest()
    {
        $data = [
            'currency' => ['iso' => 'XOF'],
            'amount' => 1000,
            'mode' => 'mtn',
            'receipt_info' => 'Just a test',
            'sourceable_id' => '1672',
        ];

        $body = [
            'v1/payment_request' => [
                'klass' => 'v1/payment_request',
                'id' => 13,
                'balance_amount_before' => 5487905,
                'balance_amount_after' => 5486905,
                'status' => 'pending',
                'amount' => 1000,
                'mode' => 'mtn',
                'receipt_info' => 'Just a test',
                'commission' => '0.0',
                'fixed_commission' => 0,
                'fees' => 0,
                'amount_transferred' => 1000,
                'sourceable_id' => 1672,
                'sourceable_type' => 'V1::MobileAccount',
                'approved_at' => null,
                'transferred_at' => null,
                'to_transfer' => false,
                'declined_at' => null,
                'canceled_at' => '2026-04-27T11:43:08.654+01:00',
                'created_at' => '2025-05-22T15:57:35.656+01:00',
                'updated_at' => '2026-04-27T11:43:08.685+01:00',
                'freezed_at' => null,
                'approved_user_id' => null,
                'transferred_user_id' => null,
                'metadata' => [],
                'user_id' => 200,
                'balance_id' => 19796,
                'reference' => '1540308110435',
                'transaction_key' => null,
                'declined_reason' => null,
                'flags' => null,
                'created_via' => 'manual'
            ]
        ];

        $this->mockRequest('post', '/v1/payment_requests', $data, $body);

        $payment_request = \FedaPay\PaymentRequest::create($data);

        $this->assertInstanceOf(\FedaPay\PaymentRequest::class, $payment_request);
        $this->assertEquals(13, $payment_request->id);
        $this->assertEquals('1540308110435', $payment_request->reference);
        $this->assertEquals(1000, $payment_request->amount);
        $this->assertEquals('pending', $payment_request->status);
        $this->assertEquals('mtn', $payment_request->mode);
    }

    /**
     * Should return array of FedaPay\PaymentRequest
     */
    public function testShouldCreatePaymentRequestInBatch()
    {
        $data = [
            'payment_requests' => [
                'currency' => ['iso' => 'XOF'],
                'amount' => 1000,
                'mode' => 'mtn',
                'receipt_info' => 'Just a test',
                'sourceable_id' => '1672',
            ],
        ];

        $body = [
            'v1/payment_request_batch' => [
                'klass' => 'v1/payment_request_batch',
                'payment_requests' => [
                    [
                        'id' => 13,
                        'balance_amount_before' => 5487905,
                        'balance_amount_after' => 5486905,
                        'status' => 'pending',
                        'amount' => 1000,
                        'mode' => 'mtn',
                        'receipt_info' => 'Just a test',
                        'commission' => '0.0',
                        'fixed_commission' => 0,
                        'fees' => 0,
                        'amount_transferred' => 1000,
                        'sourceable_id' => 1672,
                        'sourceable_type' => 'V1::MobileAccount',
                        'approved_at' => null,
                        'transferred_at' => null,
                        'to_transfer' => false,
                        'declined_at' => null,
                        'canceled_at' => '2026-04-27T11:43:08.654+01:00',
                        'created_at' => '2025-05-22T15:57:35.656+01:00',
                        'updated_at' => '2026-04-27T11:43:08.685+01:00',
                        'freezed_at' => null,
                        'approved_user_id' => null,
                        'transferred_user_id' => null,
                        'metadata' => [],
                        'user_id' => 200,
                        'balance_id' => 19796,
                        'reference' => '1540308110435',
                        'transaction_key' => null,
                        'declined_reason' => null,
                        'flags' => null,
                        'created_via' => 'manual'
                    ]
                ],
                'errors' => []
            ]
        ];

        $this->mockRequest('post', '/v1/payment_requests/batch', $data, $body);

        $object = \FedaPay\PaymentRequest::createInBatch($data);

        $this->assertInstanceOf(\FedaPay\FedaPayObject::class, $object);
        $this->assertEquals(13, $object->payment_requests[0]->id);
        $this->assertEquals('1540308110435', $object->payment_requests[0]->reference);
        $this->assertEquals(1000, $object->payment_requests[0]->amount);
        $this->assertEquals('pending', $object->payment_requests[0]->status);
        $this->assertEquals('mtn', $object->payment_requests[0]->mode);
    }

    /**
     * Should retrieve a payment request
     */
    public function testShouldRetrievedAPaymentRequest()
    {
        $body = [
            'v1/payment_request' => [
                'klass' => 'v1/payment_request',
                'id' => 13,
                'balance_amount_before' => 5487905,
                'balance_amount_after' => 5486905,
                'status' => 'pending',
                'amount' => 1000,
                'mode' => 'mtn',
                'receipt_info' => 'Just a test',
                'commission' => '0.0',
                'fixed_commission' => 0,
                'fees' => 0,
                'amount_transferred' => 1000,
                'sourceable_id' => 1672,
                'sourceable_type' => 'V1::MobileAccount',
                'approved_at' => null,
                'transferred_at' => null,
                'to_transfer' => false,
                'declined_at' => null,
                'canceled_at' => '2026-04-27T11:43:08.654+01:00',
                'created_at' => '2025-05-22T15:57:35.656+01:00',
                'updated_at' => '2026-04-27T11:43:08.685+01:00',
                'freezed_at' => null,
                'approved_user_id' => null,
                'transferred_user_id' => null,
                'metadata' => [],
                'user_id' => 200,
                'balance_id' => 19796,
                'reference' => '1540308110435',
                'transaction_key' => null,
                'declined_reason' => null,
                'flags' => null,
                'created_via' => 'manual'
            ]
        ];

        $this->mockRequest('get', '/v1/payment_requests/13', [], $body);

        $payment_request = \FedaPay\PaymentRequest::retrieve(13);

        $this->assertInstanceOf(\FedaPay\PaymentRequest::class, $payment_request);
        $this->assertEquals(13, $payment_request->id);
        $this->assertEquals('1540308110435', $payment_request->reference);
        $this->assertEquals(1000, $payment_request->amount);
        $this->assertEquals('pending', $payment_request->status);
        $this->assertEquals('mtn', $payment_request->mode);
    }

    /**
     * Should update a payment request
     */
    public function testShouldUpdateAPaymentRequest()
    {
        $data = [
            'currency' => ['iso' => 'XOF'],
            'amount' => 1000,
            'mode' => 'mtn',
            'receipt_info' => 'Just a test',
            'sourceable_id' => '1672',
        ];
        $body = [
            'v1/payment_request' => [
                'klass' => 'v1/payment_request',
                'id' => 13,
                'balance_amount_before' => 5487905,
                'balance_amount_after' => 5486905,
                'status' => 'pending',
                'amount' => 1000,
                'mode' => null,
                'receipt_info' => 'Just a test',
                'commission' => '0.0',
                'fixed_commission' => 0,
                'fees' => 0,
                'amount_transferred' => 1000,
                'sourceable_id' => 1672,
                'sourceable_type' => 'V1::MobileAccount',
                'approved_at' => null,
                'transferred_at' => null,
                'to_transfer' => false,
                'declined_at' => null,
                'canceled_at' => '2026-04-27T11:43:08.654+01:00',
                'created_at' => '2025-05-22T15:57:35.656+01:00',
                'updated_at' => '2026-04-27T11:43:08.685+01:00',
                'freezed_at' => null,
                'approved_user_id' => null,
                'transferred_user_id' => null,
                'metadata' => [],
                'user_id' => 200,
                'balance_id' => 19796,
                'reference' => '1540308110435',
                'transaction_key' => null,
                'declined_reason' => null,
                'flags' => null,
                'created_via' => 'manual'
            ]
        ];

        $this->mockRequest('put', '/v1/payment_requests/13', $data, $body);

        $payment_request = \FedaPay\PaymentRequest::update(13, $data);

        $this->assertInstanceOf(\FedaPay\PaymentRequest::class, $payment_request);
        $this->assertEquals(13, $payment_request->id);
        $this->assertEquals('1540308110435', $payment_request->reference);
        $this->assertEquals(1000, $payment_request->amount);
        $this->assertEquals('pending', $payment_request->status);
        $this->assertEquals(null, $payment_request->mode);
    }

    /**
     * Should update a payment request with save
     */
    public function testShouldUpdateAPaymentRequestWithSave()
    {
        $data = [
            'currency' => ['iso' => 'XOF'],
            'amount' => 1000,
            'mode' => 'mtn',
            'receipt_info' => 'Just a test',
            'sourceable_id' => '1672',
        ];

        $body = [
            'v1/payment_request' => [
                'klass' => 'v1/payment_request',
                'id' => 13,
                'status' => 'pending',
                'amount' => 1000,
                'mode' => 'mtn',
                'receipt_info' => 'Just a test',
                'created_at' => '2025-05-22T15:57:35.656+01:00',
                'updated_at' => '2026-04-27T11:43:08.685+01:00',
                'currency' => [
                    'klass' => 'v1/currency',
                    'iso' => 'XOF'
                ],
            ]
        ];

        $this->mockRequest('post', '/v1/payment_requests', $data, $body);

        $payment_request = \FedaPay\PaymentRequest::create($data);
        $payment_request->amount = 5000;

        $updateData = [
            'klass' => 'v1/payment_request',
            'status' => 'pending',
            'amount' => 5000,
            'mode' => 'mtn',
            'receipt_info' => 'Just a test',
            'created_at' => '2025-05-22T15:57:35.656+01:00',
            'updated_at' => '2026-04-27T11:43:08.685+01:00',
            'currency' => [
                'klass' => 'v1/currency',
                'iso' => 'XOF'
            ],
        ];

        $this->mockRequest('put', '/v1/payment_requests/13', $updateData, $body);
        $payment_request->save();
    }

    /**
     * Should delete a payment request
     */
    public function testShouldDeleteAPaymentRequest()
    {
        $data = [
            'currency' => ['iso' => 'XOF'],
            'amount' => 1000,
            'mode' => 'mtn',
            'receipt_info' => 'Just a test',
            'sourceable_id' => '1672',
        ];

        $body = [
            'v1/payment_request' => [
                'klass' => 'v1/payment_request',
                'id' => 13,
                'balance_amount_before' => 5487905,
                'balance_amount_after' => 5486905,
                'status' => 'pending',
                'amount' => 1000,
                'mode' => 'mtn',
                'receipt_info' => 'Just a test',
                'commission' => '0.0',
                'fixed_commission' => 0,
                'fees' => 0,
                'amount_transferred' => 1000,
                'sourceable_id' => 1672,
                'sourceable_type' => 'V1::MobileAccount',
                'approved_at' => null,
                'transferred_at' => null,
                'to_transfer' => false,
                'declined_at' => null,
                'canceled_at' => '2026-04-27T11:43:08.654+01:00',
                'created_at' => '2025-05-22T15:57:35.656+01:00',
                'updated_at' => '2026-04-27T11:43:08.685+01:00',
                'freezed_at' => null,
                'approved_user_id' => null,
                'transferred_user_id' => null,
                'metadata' => [],
                'user_id' => 200,
                'balance_id' => 19796,
                'reference' => '1540308110435',
                'transaction_key' => null,
                'declined_reason' => null,
                'flags' => null,
                'created_via' => 'manual'
            ]
        ];

        $this->mockRequest('post', '/v1/payment_requests', $data, $body);
        $payment_request = \FedaPay\PaymentRequest::create($data);

        $this->mockRequest('delete', '/v1/payment_requests/13');
        $payment_request->delete();
    }
}
