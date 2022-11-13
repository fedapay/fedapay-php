<?php

namespace Tests;

class AccountTest extends BaseTestCase
{
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

        $this->mockRequest('get', '/v1/accounts', [], $body);

        $object = \FedaPay\Account::all();

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

        $this->mockRequest('post', '/v1/accounts', $data, $body);

        $account = \FedaPay\Account::create($data);

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

        $this->mockRequest('get', '/v1/accounts/1', [], $body);

        $account = \FedaPay\Account::retrieve(1);

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

        $this->mockRequest('put', '/v1/accounts/1', $data, $body);

        $account = \FedaPay\Account::update(1, $data);

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

        $this->mockRequest('post', '/v1/accounts', $data, $body);
        $account = \FedaPay\Account::create($data);
        $account->name = 'Updated Name';

        $updateData = [
            'country' => 'BJ',
            'created_at' => '2018-03-12T09:09:03.969Z',
            'klass' => 'v1/account',
            'name' => 'Updated Name',
            'timezone' => 'UTC',
            'updated_at' => '2018-03-12T09:09:03.969Z'
        ];

        $this->mockRequest('put', '/v1/accounts/1', $updateData, $body);

        $account->save();
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

        $this->mockRequest('post', '/v1/accounts', $data, $body);

        $account = \FedaPay\Account::create($data);

        $this->mockRequest('delete', '/v1/accounts/1');

        $account->delete();
    }
}
