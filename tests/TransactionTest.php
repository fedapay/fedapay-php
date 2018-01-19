<?php

namespace Tests;

use Faker\Factory;

class TransactionTest extends BaseTestCase
{
    private $customerId = 1;

    /**
     * Should return array of Fedapay\Transaction
     */
    public function testShouldReturnTransactions()
    {
        $object = \Fedapay\Transaction::all();

        $this->assertInstanceOf(\Fedapay\FedapayObject::class, $object);
        $this->assertInstanceOf(\Fedapay\FedapayObject::class, $object->meta);
        $this->assertTrue(is_array($object->transactions));
    }

    /**
     * Should return array of Fedapay\Transaction
     */
    public function testTransactionCreationShouldFailed()
    {
        try {
            \Fedapay\Transaction::create([
                'customer' => ['id' => $this->customerId],
                'currency' => ['iso' => 'XOF']
            ]);
        } catch (\Fedapay\Error\ApiConnection $e) {
            $this->assertTrue($e->hasErrors());
            $this->assertNotNull($e->getErrorMessage());
            $errors = $e->getErrors();
            $this->assertArrayHasKey('description', $errors);
            $this->assertArrayHasKey('items', $errors);
            $this->assertArrayHasKey('amount', $errors);
            $this->assertArrayHasKey('callback_url', $errors);
        }
    }

    /**
     * Should return array of Fedapay\Transaction
     */
    public function testShouldCreateATransaction()
    {
        $faker = Factory::create();
        $data = [
            'customer' => ['id' => $this->customerId],
            'currency' => ['iso' => 'XOF'],
            'description' => 'Description',
            'callback_url' => $faker->url,
            'amount' => 1000,
            'items' => 1,
            'include' => 'customer,currency'
        ];

        $transaction = \Fedapay\Transaction::create($data);

        $this->assertInstanceOf(\Fedapay\Transaction::class, $transaction);
        $this->assertEquals($transaction->description, $data['description']);
        $this->assertEquals($transaction->callback_url, $data['callback_url']);
        $this->assertEquals($transaction->amount, $data['amount']);
        $this->assertEquals($transaction->items, $data['items']);
        $this->assertEquals($transaction->customer->id, $data['customer']['id']);
        $this->assertEquals($transaction->currency->iso, $data['currency']['iso']);
    }

    /**
     * Should retrieve a Transaction
     */
    public function testShouldRetrievedATransaction()
    {
        $faker = Factory::create();
        $data = [
            'customer' => ['id' => $this->customerId],
            'currency' => ['iso' => 'XOF'],
            'description' => 'Description',
            'callback_url' => $faker->url,
            'amount' => 1000,
            'items' => 1,
            'include' => 'customer,currency'
        ];

        $transaction = \Fedapay\Transaction::create($data);

        $retrieveTransaction = \Fedapay\Transaction::retrieve($transaction->id);

        $this->assertInstanceOf(\Fedapay\Transaction::class, $retrieveTransaction);
        $this->assertEquals($retrieveTransaction->description, $data['description']);
        $this->assertEquals($retrieveTransaction->callback_url, $data['callback_url']);
        $this->assertEquals($retrieveTransaction->amount, $data['amount']);
        $this->assertEquals($retrieveTransaction->items, $data['items']);
    }

    /**
     * Should update a transaction
     */
    public function testShouldUpdateATransaction()
    {
        $faker = Factory::create();
        $data = [
            'customer' => ['id' => $this->customerId],
            'currency' => ['iso' => 'XOF'],
            'description' => 'Description',
            'callback_url' => $faker->url,
            'amount' => 1000,
            'items' => 1,
        ];

        $updatedData = [
            'description' => 'Updated description',
            'callback_url' => $faker->url,
            'amount' => 10000,
            'items' => 2,
        ];

        $transaction = \Fedapay\Transaction::create($data);
        $updatedTransaction = \Fedapay\Transaction::update($transaction->id, $updatedData);

        $this->assertInstanceOf(\Fedapay\Transaction::class, $updatedTransaction);
        $this->assertEquals($updatedTransaction->description, $updatedData['description']);
        $this->assertEquals($updatedTransaction->callback_url, $updatedData['callback_url']);
        $this->assertEquals($updatedTransaction->amount, $updatedData['amount']);
        $this->assertEquals($updatedTransaction->items, $updatedData['items']);
    }

    /**
     * Should update a transaction with save
     */
    public function testShouldUpdateATransactionWithSave()
    {
        $faker = Factory::create();
        $transaction = \Fedapay\Transaction::create([
            'customer' => ['id' => $this->customerId],
            'currency' => ['iso' => 'XOF'],
            'description' => 'Description',
            'callback_url' => $faker->url,
            'amount' => 1000,
            'items' => 1,
        ]);

        $updatedData = [
            'description' => 'Updated description',
            'callback_url' => $faker->url,
            'amount' => 10000,
            'items' => 2,
        ];

        $transaction->description = $updatedData['description'];
        $transaction->callback_url = $updatedData['callback_url'];
        $transaction->amount = $updatedData['amount'];
        $transaction->items = $updatedData['items'];

        $transaction->save();

        $this->assertEquals($transaction->description, $updatedData['description']);
        $this->assertEquals($transaction->callback_url, $updatedData['callback_url']);
        $this->assertEquals($transaction->amount, $updatedData['amount']);
        $this->assertEquals($transaction->items, $updatedData['items']);
    }

    /**
     * Should delete a transaction
     */
    public function testShouldDeleteATransaction()
    {
        $faker = Factory::create();
        $transaction = \Fedapay\Transaction::create([
            'customer' => ['id' => $this->customerId],
            'currency' => ['iso' => 'XOF'],
            'description' => 'Description',
            'callback_url' => $faker->url,
            'amount' => 1000,
            'items' => 1,
        ]);

        $transaction->delete();

        $this->setExpectedException(\Fedapay\Error\ApiConnection::class);
        \Fedapay\Transaction::retrieve($transaction->id);
    }
}
