<?php

namespace Tests;

class CurrencyTest extends BaseTestCase
{
    /**
     * Should return FedaPay\Currency
     */
    public function testShouldReturnCurrencies()
    {
        $body = [
            'v1/currencies' => [[
                'id' => 1,
                'klass' => 'v1/currency',
                'name' => 'FCFA',
                'iso' => 'XOF',
                'code' => 952,
                'prefix' => null,
                'suffix' => 'CFA',
                'div' => 1,
                'created_at' => '2018-03-12T09:09:03.969Z',
                'updated_at' => '2018-03-12T09:09:03.969Z'
            ]]
        ];

        $this->mockRequest('get', '/v1/currencies', [], $body);

        $object = \FedaPay\Currency::all();

        $this->assertInstanceOf(\FedaPay\FedaPayObject::class, $object);
        $this->assertTrue(is_array($object->currencies));
        $this->assertInstanceOf(\FedaPay\Currency::class, $object->currencies[0]);
        $this->assertEquals('FCFA', $object->currencies[0]->name);
        $this->assertEquals('XOF', $object->currencies[0]->iso);
        $this->assertEquals(952, $object->currencies[0]->code);
        $this->assertEquals(null, $object->currencies[0]->prefix);
        $this->assertEquals('CFA', $object->currencies[0]->suffix);
        $this->assertEquals(1, $object->currencies[0]->div);
    }

    /**
     * Should retrieve a Customer
     */
    public function testShouldRetrievedACurrency()
    {
        $body = [
            'v1/currency' => [
                'id' => 1,
                'klass' => 'v1/currency',
                'name' => 'FCFA',
                'iso' => 'XOF',
                'code' => 952,
                'prefix' => null,
                'suffix' => 'CFA',
                'div' => 1,
                'created_at' => '2018-03-12T09:09:03.969Z',
                'updated_at' => '2018-03-12T09:09:03.969Z'
            ]
        ];

        $this->mockRequest('get', '/v1/currencies/1', [], $body);

        $currency = \FedaPay\Currency::retrieve(1);

        $this->assertInstanceOf(\FedaPay\Currency::class, $currency);
        $this->assertEquals('FCFA', $currency->name);
        $this->assertEquals('XOF', $currency->iso);
        $this->assertEquals(952, $currency->code);
        $this->assertEquals(null, $currency->prefix);
        $this->assertEquals('CFA', $currency->suffix);
        $this->assertEquals(1, $currency->div);
    }
}
