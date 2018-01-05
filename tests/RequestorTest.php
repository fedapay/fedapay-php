<?php

namespace Tests;

class RequestorTest extends BaseTestCase
{
    /**
     * Should return the right class name
     * @return void
     */
    public function testReturnClassName()
    {
        $this->assertEquals(Fixtures\Foo::className(), 'foo');
        $this->assertEquals(Fixtures\Foo_Test::className(), 'footest');
    }
}
