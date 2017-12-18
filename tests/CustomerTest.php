<?php

namespace Fedapay;

class CustomerTest extends Test
{
    public function testDeletion()
    {
        $customer = self::createTestCustomer();
        $customer->delete();

        $this->assertTrue($customer->deleted);
        $this->assertNull($customer['firstname']);
    }

    public function testSave()
    {
        $customer = self::createTestCustomer();

        $customer->save();
        $this->assertSame($customer->email, 'toto@gmail.com');

        $fedapayCustomer = Customer::retrieve($customer->id);
        $this->assertSame($customer->email, $fedapayCustomer->email);

        Fedapay::setApiKey(null);
        $customer = Customer::create(null, self::API_KEY);
        $customer->save();

        self::authorizeFromEnv();
        $updatedCustomer = Customer::retrieve($customer->id);
        $this->assertSame($updatedCustomer->email, 'toto@gmail.com');
    }

}
