<?php

namespace Fedapay;


class UtilTest extends Test
{


    public function testConvertFedapayObjectToArrayIncludesId()
    {
        $customer = self::createTestCustomer();
        $this->assertTrue(array_key_exists("id", $customer->__toArray(true)));
    }

}
