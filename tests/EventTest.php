<?php

namespace Tests;

class EventTest extends BaseTestCase
{
    /**
     * Should return array of FedaPay\Event
     */
    public function testShouldReturnEvents()
    {
        $body = [
            'v1/events' => [[
                'id' => 1,
                'klass' => 'v1/event',
                'type' => 'transaction.update',
                'entity' => [],
                'object_id' => 1,
                'account_id' => 1,
                'created_at' => '2018-03-12T09:09:03.969Z',
                'updated_at' => '2018-03-12T09:09:03.969Z'
            ]],
            'meta' => ['page' => 1]
        ];

        $this->mockRequest('get', '/v1/events', [], $body);

        $object = \FedaPay\Event::all();

        $this->assertInstanceOf(\FedaPay\FedaPayObject::class, $object);
        $this->assertInstanceOf(\FedaPay\FedaPayObject::class, $object->meta);
        $this->assertTrue(is_array($object->events));
        $this->assertInstanceOf(\FedaPay\Event::class, $object->events[0]);
        $this->assertEquals(1, $object->events[0]->id);
        $this->assertEquals('transaction.update', $object->events[0]->type);
        $this->assertEquals(1, $object->events[0]->object_id);
        $this->assertEquals(1, $object->events[0]->account_id);
    }

    /**
     * Should retrieve a Event
     */
    public function testShouldRetrievedAEvent()
    {
        $body = [
            'v1/event' => [
                'id' => 1,
                'klass' => 'v1/event',
                'type' => 'transaction.update',
                'entity' => [],
                'object_id' => 1,
                'account_id' => 1,
                'created_at' => '2018-03-12T09:09:03.969Z',
                'updated_at' => '2018-03-12T09:09:03.969Z'
            ]
        ];

        $this->mockRequest('get', '/v1/events/1', [], $body);

        $event = \FedaPay\Event::retrieve(1);

        $this->assertInstanceOf(\FedaPay\Event::class, $event);
        $this->assertEquals(1, $event->id);
        $this->assertEquals('transaction.update', $event->type);
        $this->assertEquals(1, $event->object_id);
        $this->assertEquals(1, $event->account_id);
    }
}
