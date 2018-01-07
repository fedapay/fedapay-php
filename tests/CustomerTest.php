<?php

namespace Tests;

class CustomerTest extends BaseTestCase
{
    public function testGetAllCustomers()
    {
        $object = \Fedapay\Customer::all();

        $this->assertInstanceOf(\Fedapay\FedapayObject::class, $object);
        $this->assertInstanceOf(\Fedapay\FedapayObject::class, $object);
    }
}
