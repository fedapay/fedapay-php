<?php

namespace Tests;

class CustomerTest extends BaseTestCase
{
    public function testGetAllCustomers()
    {
        var_dump(\Fedapay\Customer::all());
    }
}
