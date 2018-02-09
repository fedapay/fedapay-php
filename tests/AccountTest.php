<?php

namespace Tests;

use Faker\Factory;

class AccountTest extends BaseTestCase
{
    const TOKEN = '442b65ffd0b82104900735bda5627414255adc0d63d6135d06fe7c68100eed81';

    protected function setUp()
    {
        \Fedapay\Fedapay::setApiKey(null);
        \Fedapay\Fedapay::setToken(self::TOKEN);
    }

    /**
     * Should return array of Fedapay\Account
     */
    public function testShouldReturnAccounts()
    {
        $object = \Fedapay\Account::all();

        $this->assertInstanceOf(\Fedapay\FedapayObject::class, $object);
        $this->assertTrue(is_array($object->accounts));
        $this->assertInstanceOf(\Fedapay\Account::class, $object->accounts[0]);
    }

    /**
     * Should return array of Fedapay\Account
     */
    public function testAccountCreationShouldFailed()
    {
        try {
            \Fedapay\Account::create(['firstname' => 'Myfirstname']);
        } catch (\Fedapay\Error\ApiConnection $e) {
            $this->assertTrue($e->hasErrors());
            $this->assertNotNull($e->getErrorMessage());
            $errors = $e->getErrors();
            $this->assertArrayHasKey('name', $errors);
        }
    }

    /**
     * Should return array of Fedapay\Account
     */
    public function testShouldCreateAAccount()
    {
        $faker = Factory::create();
        $data = [
            'name' => $faker->company
        ];

        $account = \Fedapay\Account::create($data);

        $this->assertInstanceOf(\Fedapay\Account::class, $account);
        $this->assertEquals($account->name, $data['name']);
    }

    /**
     * Should retrieve a Account
     */
    public function testShouldRetrievedAAccount()
    {
        $faker = Factory::create();
        $data = [
            'name' => $faker->company,
        ];

        $account = \Fedapay\Account::create($data);
        $retrieveAccount = \Fedapay\Account::retrieve($account->id);

        $this->assertInstanceOf(\Fedapay\Account::class, $retrieveAccount);
        $this->assertEquals($retrieveAccount->name, $data['name']);
    }

    /**
     * Should update a account
     */
    public function testShouldUpdateAAccount()
    {
        $faker = Factory::create();
        $data = [
            'name' => $faker->company
        ];

        $updatedData = [
            'name' => $faker->company,
        ];

        $account = \Fedapay\Account::create($data);
        $updatedAccount = \Fedapay\Account::update($account->id, $updatedData);

        $this->assertInstanceOf(\Fedapay\Account::class, $updatedAccount);
        $this->assertEquals($updatedAccount->name, $updatedData['name']);
    }

    /**
     * Should update a account with save
     */
    public function testShouldUpdateAAccountWithSave()
    {
        $faker = Factory::create();
        $account = \Fedapay\Account::create([
            'name' => $faker->company,
        ]);

        $updatedData = [
            'name' => $faker->company,
        ];

        $account->name = $updatedData['name'];

        $account->save();

        $this->assertEquals($account->name, $updatedData['name']);
    }

    /**
     * Should delete a account
     */
    public function testShouldDeleteAAccount()
    {
        $faker = Factory::create();
        $account = \Fedapay\Account::create([
            'name' => $faker->company,
        ]);

        $account->delete();

        $this->setExpectedException(\Fedapay\Error\ApiConnection::class);
        \Fedapay\Account::retrieve($account->id);
    }
}
