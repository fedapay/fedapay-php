<?php

namespace Tests;

use Faker\Factory;

class CurrencyTest extends BaseTestCase
{
    /**
     * Should return Fedapay\Currency
     */
    public function testShouldReturnCustomers()
    {
        $object = \Fedapay\Currency::all();

        $this->assertInstanceOf(\Fedapay\FedapayObject::class, $object);
        $this->assertTrue(is_array($object->currencies));
    }

    /**
     * Should retrieve a Customer
     */
    public function testShouldRetrievedACustomer()
    {
        $object = \Fedapay\Currency::all();
        $currency = $object->currencies[0];

        $retrieveCurrency = \Fedapay\Currency::retrieve($currency->id);

        $this->assertInstanceOf(\Fedapay\Currency::class, $retrieveCurrency);
        $this->assertEquals($retrieveCurrency->name, $currency->name);
        $this->assertEquals($retrieveCurrency->iso, $currency->iso);
        $this->assertEquals($retrieveCurrency->code, $currency->code);
        $this->assertEquals($retrieveCurrency->prefix, $currency->prefix);
        $this->assertEquals($retrieveCurrency->suffix, $currency->suffix);
        $this->assertEquals($retrieveCurrency->div, $currency->div);
    }
}
