<?php

namespace Tests;

class EventTest extends BaseTestCase
{
    /**
     * Should return array of Fedapay\Event
     */
    public function testShouldReturnEvents()
    {
        $object = \Fedapay\Event::all();

        $this->assertInstanceOf(\Fedapay\FedapayObject::class, $object);
        $this->assertInstanceOf(\Fedapay\FedapayObject::class, $object->meta);
        $this->assertTrue(is_array($object->events));
        $this->assertInstanceOf(\Fedapay\Event::class, $object->events[0]);
    }

    /**
     * Should retrieve a Event
     */
    public function testShouldRetrievedAEvent()
    {
        $object = \Fedapay\Event::all();
        $events = $object->events;
        $event = $events[0];

        $retrieveEvent = \Fedapay\Event::retrieve($event->id);

        $this->assertInstanceOf(\Fedapay\Event::class, $retrieveEvent);
        $this->assertEquals($retrieveEvent->type, $event->type);
        $this->assertEquals($retrieveEvent->entity, $event->entity);
        $this->assertEquals($retrieveEvent->object_id, $event->object_id);
        $this->assertEquals($retrieveEvent->account_id, $event->account_id);
        $this->assertEquals($retrieveEvent->object, $event->object);
    }
}
