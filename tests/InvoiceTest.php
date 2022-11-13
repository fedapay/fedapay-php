<?php

namespace Tests;

use Faker\Factory;

class InvoiceTest extends BaseTestCase
{
    /**
     * Should return array of FedaPay\Invoice
     */
    public function testShouldReturnInvoices()
    {
        $body = [
            'v1/invoices' => [[
                'id' => 2,
                'klass' => 'v1/invoice',
                'number' => '2',
                'reference' => 'v_hZUvFT',
                'status' => 'sent',
                'tax' => 0,
                'discount_type' => 'percentage',
                'discount_amount' => 0,
                'ttc' => 2500,
                'sub_total' => 0,
                'discount' => 0,
                'before_tax' => 0,
                'tax_amount' => 0,
                'total_amount_paid' => 2500,
                'notes' => 'hi just a test',
                'invoice_products_count' => 1,
                'due_at' => '2018-03-12T09:09:03.969Z',
                'currency' => [
                    'klass' => 'v1/currency',
                    'id' => 1,
                    'klass' => 'v1/currency',
                    'name' => 'FCFA',
                    'iso' => 'XOF',
                    'code' => 952,
                    'prefix' => null,
                    'suffix' => 'CFA',
                    'div' => 1,
                    'created_at' => '2018-03-12T09:09:03.969Z',
                    'updated_at' => '2018-03-12T09:09:03.969Z'
                ],

            ]],
            'meta' => [
                'current_page' => 1,
                'next_page' => null,
                'prev_page' => null,
                'total_pages' => 1,
                'total_count' => 1,
                'per_page' => 25,
            ]
        ];

        $this->mockRequest('get', '/v1/invoices', [], $body);

        $object = \FedaPay\Invoice::all();

        $this->assertTrue(is_array($object->invoices));
        $this->assertInstanceOf(\FedaPay\FedaPayObject::class, $object);
        $this->assertInstanceOf(\FedaPay\FedaPayObject::class, $object->meta);
        $this->assertInstanceOf(\FedaPay\Invoice::class, $object->invoices[0]);
        $this->assertEquals(2, $object->invoices[0]->number);
        $this->assertEquals('v_hZUvFT', $object->invoices[0]->reference);
        $this->assertEquals('hi just a test', $object->invoices[0]->notes);
        $this->assertInstanceOf(\FedaPay\FedaPayObject::class, $object->invoices[0]->currency);
    }

    /**
     * Should faild creating the invoice
     */
    public function testInvoiceCreationShouldFailed()
    {
        $data = [
            'number' => 1,
            'notes' => 'My note'
        ];

        $body = [
            'message' => 'Invoice creation failed',
            'errors' => [
                'notes' => ['notes field required']
            ]
        ];

        $this->mockRequest('post', '/v1/invoices', $data, $body, 500);

        try {
            \FedaPay\Invoice::create($data);
        } catch (\FedaPay\Error\ApiConnection $e) {
            $this->assertTrue($e->hasErrors());
            $this->assertNotNull($e->getErrorMessage());
            $errors = $e->getErrors();
            $this->assertArrayHasKey('notes', $errors);
        }
    }

    /**
     * Should create an invoice
     */
    public function testShouldCreateAnInvoice()
    {
        $data = [
            'number' => 4,
            'reference' => 'reference-invoice',
            'notes' => 'Invoice content',
            'currency_id' => 1,
            'account_id' => 1
        ];

        $body = [
            'v1/invoice' => [
                'id' => 1,
                'klass' => 'v1/invoice',
                'number' => $data['number'],
                'reference' => $data['reference'],
                'notes' => $data['notes'],
                'due_at' => '2018-03-12T09:09:03.969Z',
                'created_at' => '2019-11-19T10:19:03.969Z',
                'updated_at' => '2019-11-19T10:19:03.969Z'
            ]
        ];
        $this->mockRequest('post', '/v1/invoices', $data, $body);

        $invoice = \FedaPay\Invoice::create($data);
        $this->assertInstanceOf(\FedaPay\Invoice::class, $invoice);
        $this->assertEquals($invoice->number, $data['number']);
        $this->assertEquals($invoice->reference, $data['reference']);
        $this->assertEquals($invoice->notes, $data['notes']);
        $this->assertEquals($invoice->id, 1);
    }

    /**
     * Should retrieve an Invoice
     */
    public function testShouldRetrievedAnInvoice()
    {
        $data = [
            'number' => 4,
            'reference' => 'reference-invoice',
            'notes' => 'Invoice content',
            'currency_id' => 1,
            'account_id' => 1
        ];

        $body = [
            'v1/invoice' => [
                'id' => 1,
                'klass' => 'v1/invoice',
                'number' => $data['number'],
                'reference' => $data['reference'],
                'notes' => $data['notes'],
                'due_at' => '2018-03-12T09:09:03.969Z',
                'created_at' => '2019-11-19T10:19:03.969Z',
                'updated_at' => '2019-11-19T10:19:03.969Z'
            ]
        ];

        $this->mockRequest('get', '/v1/invoices/1', [], $body);

        $invoice = \FedaPay\Invoice::retrieve(1);

        $this->assertInstanceOf(\FedaPay\Invoice::class, $invoice);
        $this->assertEquals($invoice->number, $data['number']);
        $this->assertEquals($invoice->reference, $data['reference']);
        $this->assertEquals($invoice->notes, $data['notes']);
        $this->assertEquals($invoice->id, 1);
    }

     /**
      * Should update an invoice
      */
    public function testShouldUpdateAnInvoice()
    {
        $data = [
            'number' => 4,
            'reference' => 'reference-invoice',
            'notes' => 'Invoice content',
            'currency_id' => 1,
            'account_id' => 1
        ];

        $body = [
            'v1/invoice' => [
                'id' => 1,
                'klass' => 'v1/invoice',
                'number' => $data['number'],
                'reference' => $data['reference'],
                'notes' => $data['notes'],
                'due_at' => '2018-03-12T09:09:03.969Z',
                'created_at' => '2019-11-19T10:19:03.969Z',
                'updated_at' => '2019-11-19T10:19:03.969Z'
            ]
        ];

        $this->mockRequest('put', '/v1/invoices/1', $data, $body);

        $invoice = \FedaPay\Invoice::update(1, $data);

        $this->assertInstanceOf(\FedaPay\Invoice::class, $invoice);
        $this->assertEquals($invoice->number, $data['number']);
        $this->assertEquals($invoice->reference, $data['reference']);
        $this->assertEquals($invoice->notes, $data['notes']);
        $this->assertEquals($invoice->id, 1);
    }

    /**
     * Should update an invoice with save
     */
    public function testShouldUpdateAnInvoiceWithSave()
    {
        $data = [
            'number' => 1,
            'reference' => 'reference-invoice',
            'notes' => 'Invoice content',
            'currency_id' => 1,
            'account_id' => 1
        ];

        $body = [
            'v1/invoice' => [
                'id' => 1,
                'klass' => 'v1/invoice',
                'number' => $data['number'],
                'reference' => $data['reference'],
                'notes' => $data['notes'],
                'due_at' => '2018-03-12T09:09:03.969Z',
                'created_at' => '2019-11-19T10:19:03.969Z',
                'updated_at' => '2019-11-19T10:19:03.969Z'
            ]
        ];

        $this->mockRequest('post', '/v1/invoices', $data, $body);

        $invoice = \FedaPay\Invoice::create($data);
        $invoice->number = 1;
        $updateData = [
            'klass' => 'v1/invoice',
            'number' => $data['number'],
            'notes' => $data['notes'],
            'reference' => $data['reference'],
            'due_at' => '2018-03-12T09:09:03.969Z',
            'created_at' => '2019-11-19T10:19:03.969Z',
            'updated_at' => '2019-11-19T10:19:03.969Z'
        ];

        $this->mockRequest('put', '/v1/invoices/1', $updateData, $body);
        $invoice->save();
    }

    /**
     * Should delete an invoice
     */
    public function testShouldDeleteAInvoice()
    {
        $data = [
            'number' => 4,
            'reference' => 'reference-invoice',
            'notes' => 'Invoice content',
            'currency_id' => 1,
            'account_id' => 1
        ];

        $body = [
            'v1/invoice' => [
                'id' => 1,
                'klass' => 'v1/invoice',
                'number' => $data['number'],
                'reference' => $data['reference'],
                'notes' => $data['notes'],
                'due_at' => '2018-03-12T09:09:03.969Z',
                'created_at' => '2019-11-19T10:19:03.969Z',
                'updated_at' => '2019-11-19T10:19:03.969Z'
            ]
        ];

        $this->mockRequest('post', '/v1/invoices', $data, $body);
        $invoice = \FedaPay\Invoice::create($data);

        $this->mockRequest('delete', '/v1/invoices/1');

        $invoice->delete();
    }

    public function testShouldVerifyInvoice()
    {
        $data = [
            'number' => 4,
            'reference' => 'reference-invoice',
            'notes' => 'Invoice content',
            'currency_id' => 1,
            'account_id' => 1
        ];

        $body = [
            'v1/invoice' => [
                'id' => 1,
                'klass' => 'v1/invoice',
                'number' => $data['number'],
                'reference' => $data['reference'],
                'notes' => $data['notes'],
                'due_at' => '2018-03-12T09:09:03.969Z',
                'created_at' => '2019-11-19T10:19:03.969Z',
                'updated_at' => '2019-11-19T10:19:03.969Z'
            ]
        ];

        $this->mockRequest('post', '/v1/invoices', $data, $body);
        $invoice = \FedaPay\Invoice::create($data);

        $body = [
            'v1/invoice_verify' => [
                'invoice' => [
                    'id' => 1,
                    'klass' => 'v1/invoice',
                    'number' => $data['number'],
                    'reference' => $data['reference'],
                    'notes' => $data['notes'],
                    'due_at' => '2018-03-12T09:09:03.969Z',
                    'created_at' => '2019-11-19T10:19:03.969Z',
                    'updated_at' => '2019-11-19T10:19:03.969Z'
                ],
                'sesstings' => []
            ]
        ];

        $this->mockRequest('get', '/v1/invoices/' . $data['reference'] . '/verify', [], $body);
        $object = $invoice->verify($data['reference']);
        $this->assertInstanceOf(\FedaPay\Invoice::class, $object->invoice);
    }
}
