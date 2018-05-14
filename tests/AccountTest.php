<?php

namespace Tests;

use Faker\Factory;

class AccountTest extends BaseTestCase
{
    protected function setUp()
    {
        \FedaPay\FedaPay::setApiKey(null);
        \FedaPay\FedaPay::setToken(self::OAUTH_TOKEN);
    }

    /**
     * Should return array of FedaPay\Account
     */
    public function testShouldReturnAccounts()
    {
        $body = [
            'v1/accounts' => [[
                'country' => 'BJ',
                'created_at' => '2018-03-12T09:09:03.969Z',
                'id' => 1,
                'klass' => 'v1/account',
                'name' => 'Test account',
                'timezone' => 'UTC',
                'updated_at' => '2018-03-12T09:09:03.969Z'
            ]]
        ];

        $client = $this->createMockClient(200, $body);
        \FedaPay\Requestor::setHttpClient($client);
        $object = \FedaPay\Account::all();

        $this->exceptRequest('/v1/accounts', 'GET');

        $this->assertInstanceOf(\FedaPay\FedaPayObject::class, $object);
        $this->assertTrue(is_array($object->accounts));
        $this->assertInstanceOf(\FedaPay\Account::class, $object->accounts[0]);
        $this->assertEquals('Test account', $object->accounts[0]->name);
        $this->assertEquals(1, $object->accounts[0]->id);
        $this->assertEquals('UTC', $object->accounts[0]->timezone);
    }

    /**
     * Should return array of FedaPay\Account
     */
    public function testAccountCreationShouldFailed()
    {
        $body = [
            'message' => 'Account creation failed',
            'errors' => [
                'name' => ['name field required']
            ]
        ];

        $client = $this->createMockClient(500, $body);
        \FedaPay\Requestor::setHttpClient($client);

        try {
            \FedaPay\Account::create(['firstname' => 'Myfirstname']);
        } catch (\FedaPay\Error\ApiConnection $e) {
            $this->exceptRequest('/v1/accounts', 'POST');

            $this->assertTrue($e->hasErrors());
            $this->assertNotNull($e->getErrorMessage());
            $errors = $e->getErrors();
            $this->assertArrayHasKey('name', $errors);
        }
    }

    /**
     * Should return array of FedaPay\Account
     */
    public function testShouldCreateAccount()
    {
        $data = [
            'name' => 'My account'
        ];

        $body = [
            'v1/account' => [
                'country' => 'BJ',
                'created_at' => '2018-03-12T09:09:03.969Z',
                'id' => 1,
                'klass' => 'v1/account',
                'name' => $data['name'],
                'timezone' => 'UTC',
                'updated_at' => '2018-03-12T09:09:03.969Z'
            ]
        ];

        $client = $this->createMockClient(200, $body);
        \FedaPay\Requestor::setHttpClient($client);

        $account = \FedaPay\Account::create($data);

        $this->exceptRequest('/v1/accounts', 'POST');
        $this->assertInstanceOf(\FedaPay\Account::class, $account);
        $this->assertEquals($account->name, $data['name']);
    }

    /**
     * Should retrieve a Account
     */
    public function testShouldRetrievedAccount()
    {
        $body = [
            'v1/account' => [
                'country' => 'BJ',
                'created_at' => '2018-03-12T09:09:03.969Z',
                'id' => 1,
                'klass' => 'v1/account',
                'name' => 'My account',
                'timezone' => 'UTC',
                'updated_at' => '2018-03-12T09:09:03.969Z'
            ]
        ];

        $client = $this->createMockClient(200, $body);
        \FedaPay\Requestor::setHttpClient($client);

        $account = \FedaPay\Account::retrieve(1);

        $this->exceptRequest('/v1/accounts/1', 'GET');
        $this->assertInstanceOf(\FedaPay\Account::class, $account);
        $this->assertEquals($account->id, 1);
        $this->assertEquals($account->name, 'My account');
        $this->assertEquals($account->country, 'BJ');
        $this->assertEquals($account->timezone, 'UTC');
    }

    /**
     * Should update a account
     */
    public function testShouldUpdateAccount()
    {
        $data = [
            'name' => 'Updated Name',
        ];

        $body = [
            'v1/account' => [
                'country' => 'BJ',
                'created_at' => '2018-03-12T09:09:03.969Z',
                'id' => 1,
                'klass' => 'v1/account',
                'name' => $data['name'],
                'timezone' => 'UTC',
                'updated_at' => '2018-03-12T09:09:03.969Z'
            ]
        ];

        $client = $this->createMockClient(200, $body);
        \FedaPay\Requestor::setHttpClient($client);

        $account = \FedaPay\Account::update(1, $data);

        $this->exceptRequest('/v1/accounts/1', 'PUT');
        $this->assertInstanceOf(\FedaPay\Account::class, $account);
        $this->assertEquals($account->name, $data['name']);
        $this->assertEquals($account->id, 1);
        $this->assertEquals($account->country, 'BJ');
        $this->assertEquals($account->timezone, 'UTC');
    }

    /**
     * Should update a account with save
     */
    public function testShouldUpdateAccountWithSave()
    {
        $data = [
            'name' => 'Updated Name',
        ];

        $body = [
            'v1/account' => [
                'country' => 'BJ',
                'created_at' => '2018-03-12T09:09:03.969Z',
                'id' => 1,
                'klass' => 'v1/account',
                'name' => 'Name',
                'timezone' => 'UTC',
                'updated_at' => '2018-03-12T09:09:03.969Z'
            ]
        ];

        $client = $this->createMockClient(200, $body);
        \FedaPay\Requestor::setHttpClient($client);

        $account = \FedaPay\Account::create($data);

        $account->name = 'Updated Name';

        $client = $this->createMockClient(200, $body);
        \FedaPay\Requestor::setHttpClient($client);
        $account->save();

        $this->exceptRequest('/v1/accounts/1', 'PUT');
    }

    /**
     * Should delete a account
     */
    public function testShouldDeleteAccount()
    {
        $data = [
            'name' => 'My account',
        ];

        $body = [
            'v1/account' => [
                'country' => 'BJ',
                'created_at' => '2018-03-12T09:09:03.969Z',
                'id' => 1,
                'klass' => 'v1/account',
                'name' => $data['name'],
                'timezone' => 'UTC',
                'updated_at' => '2018-03-12T09:09:03.969Z'
            ]
        ];

        $client = $this->createMockClient(200, $body);
        \FedaPay\Requestor::setHttpClient($client);

        $account = \FedaPay\Account::create($data);

        $client = $this->createMockClient(200);
        \FedaPay\Requestor::setHttpClient($client);

        $account->delete();

        $this->exceptRequest('/v1/accounts/1', 'DELETE');
    }
}
