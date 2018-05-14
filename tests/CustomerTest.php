<?php

namespace Tests;

use Faker\Factory;

class CustomerTest extends BaseTestCase
{
    /**
     * Should return array of FedaPay\Customer
     */
    public function testShouldReturnCustomers()
    {
        $body = [
            'v1/customers' => [[
                'id' => 1,
                'klass' => 'v1/customer',
                'firstname' => 'John',
                'lastname' => 'Doe',
                'email' => 'john.doe@example.com',
                'phone' => '22967666776',
                'created_at' => '2018-03-12T09:09:03.969Z',
                'updated_at' => '2018-03-12T09:09:03.969Z'
            ]],
            'meta' => ['page' => 1]
        ];

        $client = $this->createMockClient(200, $body);
        \FedaPay\Requestor::setHttpClient($client);

        $object = \FedaPay\Customer::all();

        $this->exceptRequest('/v1/customers', 'GET');

        $this->assertInstanceOf(\FedaPay\FedaPayObject::class, $object);
        $this->assertInstanceOf(\FedaPay\FedaPayObject::class, $object->meta);
        $this->assertInstanceOf(\FedaPay\Customer::class, $object->customers[0]);
        $this->assertEquals('John', $object->customers[0]->firstname);
        $this->assertEquals('Doe', $object->customers[0]->lastname);
        $this->assertEquals('john.doe@example.com', $object->customers[0]->email);
        $this->assertEquals('22967666776', $object->customers[0]->phone);
    }

    /**
     * Should return array of FedaPay\Customer
     */
    public function testCustomerCreationShouldFailed()
    {
        $body = [
            'message' => 'Account creation failed',
            'errors' => [
                'lastname' => ['lastname field required']
            ]
        ];

        $client = $this->createMockClient(500, $body);
        \FedaPay\Requestor::setHttpClient($client);

        try {
            \FedaPay\Customer::create(['firstname' => 'Myfirstname']);
        } catch (\FedaPay\Error\ApiConnection $e) {
            $this->exceptRequest('/v1/customers', 'POST');

            $this->assertTrue($e->hasErrors());
            $this->assertNotNull($e->getErrorMessage());
            $errors = $e->getErrors();
            $this->assertArrayHasKey('lastname', $errors);
        }
    }

    /**
     * Should return array of FedaPay\Customer
     */
    public function testShouldCreateACustomer()
    {
        $faker = Factory::create();
        $data = [
            'firstname' => $faker->firstName,
            'lastname' => $faker->lastName,
            'email' => $faker->unique()->email,
            'phone' => $faker->phoneNumber
        ];

        $body = [
            'v1/customer' => [
                'id' => 1,
                'klass' => 'v1/customer',
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'created_at' => '2018-03-12T09:09:03.969Z',
                'updated_at' => '2018-03-12T09:09:03.969Z'
            ]
        ];

        $client = $this->createMockClient(200, $body);
        \FedaPay\Requestor::setHttpClient($client);

        $customer = \FedaPay\Customer::create($data);

        $this->exceptRequest('/v1/customers', 'POST');
        $this->assertInstanceOf(\FedaPay\Customer::class, $customer);
        $this->assertEquals($customer->firstname, $data['firstname']);
        $this->assertEquals($customer->lastname, $data['lastname']);
        $this->assertEquals($customer->email, $data['email']);
        $this->assertEquals($customer->phone, $data['phone']);
        $this->assertEquals($customer->id, 1);
    }

    /**
     * Should retrieve a Customer
     */
    public function testShouldRetrievedACustomer()
    {
        $faker = Factory::create();
        $data = [
            'firstname' => $faker->firstName,
            'lastname' => $faker->lastName,
            'email' => $faker->unique()->email,
            'phone' => $faker->phoneNumber
        ];

        $body = [
            'v1/customer' => [
                'id' => 1,
                'klass' => 'v1/customer',
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'created_at' => '2018-03-12T09:09:03.969Z',
                'updated_at' => '2018-03-12T09:09:03.969Z'
            ]
        ];

        $client = $this->createMockClient(200, $body);
        \FedaPay\Requestor::setHttpClient($client);

        $customer = \FedaPay\Customer::retrieve(1);

        $this->exceptRequest('/v1/customers/1', 'GET');
        $this->assertInstanceOf(\FedaPay\Customer::class, $customer);
        $this->assertEquals($customer->firstname, $data['firstname']);
        $this->assertEquals($customer->lastname, $data['lastname']);
        $this->assertEquals($customer->email, $data['email']);
        $this->assertEquals($customer->phone, $data['phone']);
        $this->assertEquals($customer->id, 1);
    }

    /**
     * Should update a customer
     */
    public function testShouldUpdateACustomer()
    {
        $faker = Factory::create();
        $data = [
            'firstname' => $faker->firstName,
            'lastname' => $faker->lastName,
            'email' => $faker->unique()->email,
            'phone' => $faker->phoneNumber
        ];

        $body = [
            'v1/customer' => [
                'id' => 1,
                'klass' => 'v1/customer',
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'created_at' => '2018-03-12T09:09:03.969Z',
                'updated_at' => '2018-03-12T09:09:03.969Z'
            ]
        ];

        $client = $this->createMockClient(200, $body);
        \FedaPay\Requestor::setHttpClient($client);

        $customer = \FedaPay\Customer::update(1, $data);

        $this->exceptRequest('/v1/customers/1', 'PUT');
        $this->assertInstanceOf(\FedaPay\Customer::class, $customer);
        $this->assertEquals($customer->firstname, $data['firstname']);
        $this->assertEquals($customer->lastname, $data['lastname']);
        $this->assertEquals($customer->email, $data['email']);
        $this->assertEquals($customer->phone, $data['phone']);
        $this->assertEquals($customer->id, 1);
    }

    /**
     * Should update a customer with save
     */
    public function testShouldUpdateACustomerWithSave()
    {
        $faker = Factory::create();
        $data = [
            'firstname' => $faker->firstName,
            'lastname' => $faker->lastName,
            'email' => $faker->unique()->email,
            'phone' => $faker->phoneNumber
        ];

        $body = [
            'v1/customer' => [
                'id' => 1,
                'klass' => 'v1/customer',
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'created_at' => '2018-03-12T09:09:03.969Z',
                'updated_at' => '2018-03-12T09:09:03.969Z'
            ]
        ];

        $client = $this->createMockClient(200, $body);
        \FedaPay\Requestor::setHttpClient($client);

        $customer = \FedaPay\Customer::create($data);

        $customer->firstname = 'First name';

        $client = $this->createMockClient(200, $body);
        \FedaPay\Requestor::setHttpClient($client);

        $customer->save();

        $this->exceptRequest('/v1/customers/1', 'PUT');
    }

    /**
     * Should delete a customer
     */
    public function testShouldDeleteACustomer()
    {
        $faker = Factory::create();
        $data = [
            'firstname' => $faker->firstName,
            'lastname' => $faker->lastName,
            'email' => $faker->unique()->email,
            'phone' => $faker->phoneNumber
        ];

        $body = [
            'v1/customer' => [
                'id' => 1,
                'klass' => 'v1/customer',
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'created_at' => '2018-03-12T09:09:03.969Z',
                'updated_at' => '2018-03-12T09:09:03.969Z'
            ]
        ];

        $client = $this->createMockClient(200, $body);
        \FedaPay\Requestor::setHttpClient($client);

        $customer = \FedaPay\Customer::create($data);

        $client = $this->createMockClient(200);
        \FedaPay\Requestor::setHttpClient($client);
        $customer->delete();

        $this->exceptRequest('/v1/customers/1', 'DELETE');
    }
}
