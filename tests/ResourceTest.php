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
        $this->assertEquals(Fixtures\Foo::className(), 'foo');
        $this->assertEquals(Fixtures\FooTest::className(), 'foo_test');
    }

    /**
     * Should return the right class url
     * @return void
     */
    public function testShouldReturnClassUrl()
    {
        $this->assertEquals(Fixtures\Foo::classPath(), '/foos');
        $this->assertEquals(Fixtures\FooTest::classPath(), '/foo_tests');
        $this->assertEquals(Fixtures\FooPerson::classPath(), '/foo_people');
        $this->assertEquals(Fixtures\FooCurrency::classPath(), '/foo_currencies');
    }

    /**
     * Should return throw InvalidRequest exception if id is null
     * @return void
     */
    public function testShouldThrowInvalidRequest()
    {
        $this->expectException(\FedaPay\Error\InvalidRequest::class);
        $this->expectExceptionMessage(
            'Could not determine which URL to request: Tests\Fixtures\Foo instance has invalid ID: '
        );
        Fixtures\Foo::resourcePath(null);
    }

    /**
     * Should return return resource url
     * @return void
     */
    public function testReturnResourceUrl()
    {
        $this->assertEquals(Fixtures\Foo::resourcePath(1), '/foos/1');
    }

    /**
     * Should return return resource url
     * @return void
     */
    public function testReturnInstanceUrl()
    {
        $object = new Fixtures\Foo;
        $object->id = 1;
        $this->assertEquals($object->instanceUrl(), '/foos/1');
    }
}
