<?php

namespace Tests;

use Faker\Factory;

class PageTest extends BaseTestCase
{
    /**
     * Should return array of FedaPay\Page
     */
    public function testShouldReturnPages()
    {
        $body = [
            'v1/pages' => [[
                'id' => 2,
                'klass' => 'v1/page',
                'name' => 'super Admin',
                'reference' => 'v_hZUvFT',
                'description' => 'fdfd',
                'amount' => 0,
                'published' => true,
                'enable_phone_number' => false,
                'callback_url' => null,
                'image_url' => null,
                'custom_fields' => [],
                'currency' => [
                    'klass' => 'v1/currency',
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
            ]],
            'meta' => [
                'current_page' => 1,
                'next_page' => null,
                'prev_page' => null,
                'total_pages' => 1,
                'total_count' => 1,
                'per_page' => 25,
            ]
        ];

        $this->mockRequest('get', '/v1/pages', [], $body);

        $object = \FedaPay\Page::all();

        $this->assertTrue(is_array($object->pages));
        $this->assertInstanceOf(\FedaPay\FedaPayObject::class, $object);
        $this->assertInstanceOf(\FedaPay\FedaPayObject::class, $object->meta);
        $this->assertInstanceOf(\FedaPay\Page::class, $object->pages[0]);
        $this->assertEquals('super Admin', $object->pages[0]->name);
        $this->assertEquals('v_hZUvFT', $object->pages[0]->reference);
        $this->assertEquals('fdfd', $object->pages[0]->description);
        $this->assertInstanceOf(\FedaPay\FedaPayObject::class, $object->pages[0]->currency);
    }

    /**
     * Should faild creating the page
     */
    public function testPageCreationShouldFailed()
    {
        $data = [
            'name' => 'Myname',
            'description' => 'My description'
        ];

        $body = [
            'message' => 'Page creation failed',
            'errors' => [
                'description' => ['description field required']
            ]
        ];

        $this->mockRequest('post', '/v1/pages', $data, $body, 500);

        try {
            \FedaPay\Page::create($data);
        } catch (\FedaPay\Error\ApiConnection $e) {
            $this->assertTrue($e->hasErrors());
            $this->assertNotNull($e->getErrorMessage());
            $errors = $e->getErrors();
            $this->assertArrayHasKey('description', $errors);
        }
    }

    /**
     * Should create a page
     */
    public function testShouldCreateAPage()
    {
        $data = [
            'name' => 'Page name',
            'reference' => 'page-reference',
            'description' => 'Page description',
            'currency_id' => 1
        ];

        $body = [
            'v1/page' => [
                'id' => 1,
                'klass' => 'v1/page',
                'name' => $data['name'],
                'reference' => $data['reference'],
                'description' => $data['description'],
                'created_at' => '2019-11-19T10:19:03.969Z',
                'updated_at' => '2019-11-19T10:19:03.969Z'
            ]
        ];
        $this->mockRequest('post', '/v1/pages', $data, $body);

        $page = \FedaPay\Page::create($data);
        $this->assertInstanceOf(\FedaPay\Page::class, $page);
        $this->assertEquals($page->name, $data['name']);
        $this->assertEquals($page->reference, $data['reference']);
        $this->assertEquals($page->description, $data['description']);
        $this->assertEquals($page->id, 1);
    }

    /**
     * Should retrieve a Page
     */
    public function testShouldRetrievedAPage()
    {
        $data = [
            'name' => 'Page name',
            'reference' => 'page-reference',
            'description' => 'Page description',
            'currency_id' => 1
        ];

        $body = [
            'v1/page' => [
                'id' => 1,
                'klass' => 'v1/page',
                'name' => $data['name'],
                'reference' => $data['reference'],
                'description' => $data['description'],
                'created_at' => '2019-11-19T10:19:03.969Z',
                'updated_at' => '2019-11-19T10:19:03.969Z'
            ]
        ];

        $this->mockRequest('get', '/v1/pages/1', [], $body);

        $page = \FedaPay\Page::retrieve(1);

        $this->assertInstanceOf(\FedaPay\Page::class, $page);
        $this->assertEquals($page->name, $data['name']);
        $this->assertEquals($page->reference, $data['reference']);
        $this->assertEquals($page->description, $data['description']);
        $this->assertEquals($page->id, 1);
    }

     /**
      * Should update a page
      */
    public function testShouldUpdateAPage()
    {
        $data = [
            'name' => 'Page name',
            'reference' => 'page-reference',
            'description' => 'Page description',
            'currency_id' => 1
        ];

        $body = [
            'v1/page' => [
                'id' => 1,
                'klass' => 'v1/page',
                'name' => $data['name'],
                'reference' => $data['reference'],
                'description' => $data['description'],
                'created_at' => '2019-11-19T10:19:03.969Z',
                'updated_at' => '2019-11-19T10:19:03.969Z'
            ]
        ];

        $this->mockRequest('put', '/v1/pages/1', $data, $body);

        $page = \FedaPay\Page::update(1, $data);

        $this->assertInstanceOf(\FedaPay\Page::class, $page);
        $this->assertEquals($page->name, $data['name']);
        $this->assertEquals($page->reference, $data['reference']);
        $this->assertEquals($page->description, $data['description']);
        $this->assertEquals($page->id, 1);
    }

    /**
     * Should update a page with save
     */
    public function testShouldUpdateAPageWithSave()
    {
        $data = [
            'name' => 'Page name',
            'reference' => 'page-reference',
            'description' => 'Page description',
            'currency_id' => 1
        ];

        $body = [
            'v1/page' => [
                'id' => 1,
                'klass' => 'v1/page',
                'name' => $data['name'],
                'reference' => $data['reference'],
                'description' => $data['description'],
                'created_at' => '2019-11-19T10:19:03.969Z',
                'updated_at' => '2019-11-19T10:19:03.969Z'
            ]
        ];

        $this->mockRequest('post', '/v1/pages', $data, $body);

        $page = \FedaPay\Page::create($data);
        $page->name = 'New name';
        $updateData = [
            'klass' => 'v1/page',
            'name' => 'New name',
            'reference' => $data['reference'],
            'description' => $data['description'],
            'created_at' => '2019-11-19T10:19:03.969Z',
            'updated_at' => '2019-11-19T10:19:03.969Z'
        ];

        $this->mockRequest('put', '/v1/pages/1', $updateData, $body);
        $page->save();
    }

    /**
     * Should delete a page
     */
    public function testShouldDeleteAPage()
    {
        $data = [
            'name' => 'Page name',
            'reference' => 'page-reference',
            'description' => 'Page description',
            'currency_id' => 1
        ];

        $body = [
            'v1/page' => [
                'id' => 1,
                'klass' => 'v1/page',
                'name' => $data['name'],
                'reference' => $data['reference'],
                'description' => $data['description'],
                'created_at' => '2019-11-19T10:19:03.969Z',
                'updated_at' => '2019-11-19T10:19:03.969Z'
            ]
        ];

        $this->mockRequest('post', '/v1/pages', $data, $body);
        $page = \FedaPay\Page::create($data);

        $this->mockRequest('delete', '/v1/pages/1');

        $page->delete();
    }

    public function testShouldVerifyPage()
    {
        $data = [
            'name' => 'Page name',
            'reference' => 'page-reference',
            'description' => 'Page description',
            'currency_id' => 1
        ];

        $body = [
            'v1/page' => [
                'id' => 1,
                'klass' => 'v1/page',
                'name' => $data['name'],
                'reference' => $data['reference'],
                'description' => $data['description'],
                'created_at' => '2019-11-19T10:19:03.969Z',
                'updated_at' => '2019-11-19T10:19:03.969Z'
            ]
        ];

        $this->mockRequest('post', '/v1/pages', $data, $body);
        $page = \FedaPay\Page::create($data);

        $body = [
            'v1/page_verify' => [
                'page' => [
                    'id' => 1,
                    'klass' => 'v1/page',
                    'name' => $data['name'],
                    'reference' => $data['reference'],
                    'description' => $data['description'],
                    'created_at' => '2019-11-19T10:19:03.969Z',
                    'updated_at' => '2019-11-19T10:19:03.969Z'
                ],
                'sesstings' => []
            ]
        ];

        $this->mockRequest('get', '/v1/pages/' . $data['reference'] . '/verify', [], $body);
        $object = $page->verify($data['reference']);
        $this->assertInstanceOf(\FedaPay\Page::class, $object->page);
    }
}
