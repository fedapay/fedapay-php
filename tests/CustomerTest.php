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
        } catch(\Fedapay\Error\ApiConnection $e) {
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

        $response = \Fedapay\Customer::create($data);

        $this->assertInstanceOf(\Fedapay\FedapayObject::class, $customer);
        $this->assertInstanceOf(\Fedapay\Customer::class, $response->customer);
        $this->assertEquals($response->customer->firsname, $data['firstname']);
        $this->assertEquals($response->customer->lastname, $data['lastname']);
        $this->assertEquals($response->customer->email, $data['email']);
        $this->assertEquals($response->customer->phone, $data['phone']);
    }
}
