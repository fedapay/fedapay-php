<?php

namespace Tests;

class ResourceTest extends BaseTestCase
{
    /**
     * Should return the right class name
     * @return void
     */
    public function testReturnClassName()
    {
        $this->assertEquals(Foo::className(), 'foo');
        $this->assertEquals(Foo_Test::className(), 'footest');
    }

    /**
     * Should return the right class url
     * @return void
     */
    public function testShouldReturnClassUrl()
    {
        $this->assertEquals(Foo::classUrl(), '/v1/foos');
        $this->assertEquals(Foo_Test::classUrl(), '/v1/footests');
    }

    /**
     * Should return throw InvalidRequest exception if id is null
     * @return void
     */
    public function testShouldThrowInvalidRequest()
    {
        $this->expectException(\Fedapay\Error\InvalidRequest::class);
        $this->expectExceptionMessage('Could not determine which URL to request: '.
        'Tests\Foo instance has invalid ID: ');
        Foo::resourceUrl(null);
    }
}

class Foo extends \Fedapay\Resource { }
class Foo_Test extends \Fedapay\Resource { }
