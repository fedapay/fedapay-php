<?php

namespace Tests;

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

        $this->mockRequest('get', '/v1/customers', [], $body);

        $object = \FedaPay\Customer::all();

        $this->assertTrue(is_array($object->customers));
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
        $data = ['firstname' => 'Myfirstname'];
        $body = [
            'message' => 'Account creation failed',
            'errors' => [
                'lastname' => ['lastname field required']
            ]
        ];

        $this->mockRequest('post', '/v1/customers', $data, $body, 500);

        try {
            \FedaPay\Customer::create($data);
        } catch (\FedaPay\Error\ApiConnection $e) {
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
        $data = [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '+22966000001'
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

        $this->mockRequest('post', '/v1/customers', $data, $body);

        $customer = \FedaPay\Customer::create($data);

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
        $data = [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@exemple.com',
            'phone' => '+2296600000001'
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

        $this->mockRequest('get', '/v1/customers/1', [], $body);

        $customer = \FedaPay\Customer::retrieve(1);

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
        $data = [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@exemple.com',
            'phone' => '+2296600000001'
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

        $this->mockRequest('put', '/v1/customers/1', $data, $body);

        $customer = \FedaPay\Customer::update(1, $data);

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
        $data = [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@exemple.com',
            'phone' => '+2296600000001'
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
        $this->mockRequest('post', '/v1/customers', $data, $body);
        $customer = \FedaPay\Customer::create($data);
        $customer->firstname = 'First name';
        $updateData = [
            'klass' => 'v1/customer',
            'firstname' => 'First name',
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'created_at' => '2018-03-12T09:09:03.969Z',
            'updated_at' => '2018-03-12T09:09:03.969Z'
        ];

        $this->mockRequest('put', '/v1/customers/1', $updateData, $body);
        $customer->save();
    }

    /**
     * Should delete a customer
     */
    public function testShouldDeleteACustomer()
    {
        $data = [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@exemple.com',
            'phone' => '+2296600000001'
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

        $this->mockRequest('post', '/v1/customers', $data, $body);
        $customer = \FedaPay\Customer::create($data);

        $this->mockRequest('delete', '/v1/customers/1');

        $customer->delete();
    }
}
