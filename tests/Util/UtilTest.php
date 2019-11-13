<?php

namespace Tests;

use FedaPay\Util;

class UtilTest extends BaseTestCase
{
    public function testIsList()
    {
        $list = [5, 'nstaoush', []];
        $this->assertTrue(Util\Util::isList($list));
        $notlist = [5, 'nstaoush', [], 'bar' => 'baz'];
        $this->assertFalse(Util\Util::isList($notlist));
    }

    public function testConvertFedaPayObjectToArrayIncludesId()
    {
        $customer = Util\Util::convertToFedaPayObject(
            [
                'id' => '123',
                'object' => 'v1/customer',
                'value' => ['a', 'b']
            ],
            []
        );
        $array = $customer->__toArray(true);
        $this->assertTrue(array_key_exists('id', $array));
        $this->assertEquals($array['id'], '123');
        $this->assertEquals(['a', 'b'], $array['value']);
    }

    public function testEncodeParameters()
    {
        $params = [
            'a' => 3,
            'b' => '+foo?',
            'c' => 'bar&baz',
            'd' => ['a' => 'a', 'b' => 'b'],
            'e' => [0, 1],
            'f' => '',
            // note the empty hash won't even show up in the request
            'g' => [],
        ];
        $this->assertSame(
            "a=3&b=%2Bfoo%3F&c=bar%26baz&d[a]=a&d[b]=b&e[]=0&e[]=1&f=",
            Util\Util::encodeParameters($params)
        );
    }

    public function testUrlEncode()
    {
        $this->assertSame("foo", Util\Util::urlEncode("foo"));
        $this->assertSame("foo%2B", Util\Util::urlEncode("foo+"));
        $this->assertSame("foo%26", Util\Util::urlEncode("foo&"));
        $this->assertSame("foo[bar]", Util\Util::urlEncode("foo[bar]"));
    }

    public function testFlattenParams()
    {
        $params = [
            'a' => 3,
            'b' => '+foo?',
            'c' => 'bar&baz',
            'd' => ['a' => 'a', 'b' => 'b'],
            'e' => [0, 1],
            'f' => [
                ['foo' => '1', 'ghi' => '2'],
                ['foo' => '3', 'bar' => '4'],
            ],
        ];
        $encoded = [];
        Util\Util::flattenParams($params, $encoded);

        $this->assertSame(
            [
                ['a', 3],
                ['b', '+foo?'],
                ['c', 'bar&baz'],
                ['d[a]', 'a'],
                ['d[b]', 'b'],
                ['e[]', 0],
                ['e[]', 1],
                ['f[][foo]', '1'],
                ['f[][ghi]', '2'],
                ['f[][foo]', '3'],
                ['f[][bar]', '4'],
            ],
            $encoded
        );
    }
}
