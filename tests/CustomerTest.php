<?php

namespace Tests;

use Faker\Factory;

class CustomerTest extends BaseTestCase
{
    /**
     * Should return array of Fedapay\Customer
     */
    public function testShouldReturnCustomers()
    {
        $object = \Fedapay\Customer::all();

        $this->assertInstanceOf(\Fedapay\FedapayObject::class, $object);
        $this->assertInstanceOf(\Fedapay\FedapayObject::class, $object->meta);
        $this->assertTrue(is_array($object->customers));
    }

    /**
     * Should return array of Fedapay\Customer
     */
    public function testCustomerCreationShouldFailed()
    {
        try {
            \Fedapay\Customer::create(['firstname' => 'Myfirstname']);
        } catch (\Fedapay\Error\ApiConnection $e) {
            $this->assertTrue($e->hasErrors());
            $this->assertNotNull($e->getErrorMessage());
            $errors = $e->getErrors();
            $this->assertArrayHasKey('lastname', $errors);
            $this->assertArrayHasKey('email', $errors);
            $this->assertArrayHasKey('phone', $errors);
        }
    }

    /**
     * Should return array of Fedapay\Customer
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

        $customer = \Fedapay\Customer::create($data);

        $this->assertInstanceOf(\Fedapay\Customer::class, $customer);
        $this->assertEquals($customer->firstname, $data['firstname']);
        $this->assertEquals($customer->lastname, $data['lastname']);
        $this->assertEquals($customer->email, $data['email']);
        $this->assertEquals($customer->phone, $data['phone']);
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

        $customer = \Fedapay\Customer::create($data);
        $retrieveCustomer = \Fedapay\Customer::retrieve($customer->id);

        $this->assertInstanceOf(\Fedapay\Customer::class, $retrieveCustomer);
        $this->assertEquals($retrieveCustomer->firstname, $data['firstname']);
        $this->assertEquals($retrieveCustomer->lastname, $data['lastname']);
        $this->assertEquals($retrieveCustomer->email, $data['email']);
        $this->assertEquals($retrieveCustomer->phone, $data['phone']);
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

        $updatedData = [
            'firstname' => $faker->firstName,
            'lastname' => $faker->lastName,
            'email' => $faker->unique()->email,
            'phone' => $faker->phoneNumber
        ];

        $customer = \Fedapay\Customer::create($data);
        $updatedCustomer = \Fedapay\Customer::update($customer->id, $updatedData);

        $this->assertInstanceOf(\Fedapay\Customer::class, $updatedCustomer);
        $this->assertEquals($updatedCustomer->firstname, $updatedData['firstname']);
        $this->assertEquals($updatedCustomer->lastname, $updatedData['lastname']);
        $this->assertEquals($updatedCustomer->email, $updatedData['email']);
        $this->assertEquals($updatedCustomer->phone, $updatedData['phone']);
    }

    /**
     * Should update a customer with save
     */
    public function testShouldUpdateACustomerWithSave()
    {
        $faker = Factory::create();
        $customer = \Fedapay\Customer::create([
            'firstname' => $faker->firstName,
            'lastname' => $faker->lastName,
            'email' => $faker->unique()->email,
            'phone' => $faker->phoneNumber
        ]);

        $updatedData = [
            'firstname' => $faker->firstName,
            'lastname' => $faker->lastName,
            'email' => $faker->unique()->email,
            'phone' => $faker->phoneNumber
        ];

        $customer->firstname = $updatedData['firstname'];
        $customer->lastname = $updatedData['lastname'];
        $customer->email = $updatedData['email'];
        $customer->phone = $updatedData['phone'];

        $customer->save();

        $this->assertEquals($customer->firstname, $updatedData['firstname']);
        $this->assertEquals($customer->lastname, $updatedData['lastname']);
        $this->assertEquals($customer->email, $updatedData['email']);
        $this->assertEquals($customer->phone, $updatedData['phone']);
    }

    /**
     * Should delete a customer
     */
    public function testShouldDeleteACustomer()
    {
        $faker = Factory::create();
        $customer = \Fedapay\Customer::create([
            'firstname' => $faker->firstName,
            'lastname' => $faker->lastName,
            'email' => $faker->unique()->email,
            'phone' => $faker->phoneNumber
        ]);

        $customer->delete();

        $this->setExpectedException(\Fedapay\Error\ApiConnection::class);
        \Fedapay\Customer::retrieve($customer->id);
    }
}
