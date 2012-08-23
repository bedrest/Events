<?php

namespace BedRest\Tests\Events\Driver;

use BedRest\Events\Driver\AnnotationDriver;
use BedRest\TestFixtures\Listener\AnnotationListener;
use BedRest\TestFixtures\Listener\IncompleteAnnotationListener;

/**
 * AnnotationDriverTest
 *
 * @author Geoff Adams <geoff@dianode.net>
 */
class AnnotationDriverTest extends \PHPUnit_Framework_TestCase
{
    public function testGetListeners()
    {
        $driver = new AnnotationDriver();
        
        $instance = new AnnotationListener();
        
        $listeners = $driver->getListenersForClass($instance);
        
        $this->assertInternalType('array', $listeners);
        $this->assertCount(2, $listeners);
        
        $this->assertInternalType('array', $listeners[0]);
        $this->assertArrayHasKey('event', $listeners[0]);
        $this->assertEquals('eventOne', $listeners[0]['event']);
        $this->assertArrayHasKey('method', $listeners[0]);
        $this->assertEquals('listenerOne', $listeners[0]['method']);
        
        $this->assertInternalType('array', $listeners[1]);
        $this->assertArrayHasKey('event', $listeners[1]);
        $this->assertEquals('eventTwo', $listeners[1]['event']);
        $this->assertArrayHasKey('method', $listeners[1]);
        $this->assertEquals('listenerTwo', $listeners[1]['method']);
    }
    
    public function testGetListenersNoAnnotations()
    {
        $driver = new AnnotationDriver();
        
        $instance = new \stdClass();
        
        $listeners = $driver->getListenersForClass($instance);
        
        $this->assertInternalType('array', $listeners);
        $this->assertCount(0, $listeners);
    }
    
    public function testGetListenersIncompleteDefinitionMissingEvent()
    {
        $driver = new AnnotationDriver();
        
        $instance = new IncompleteAnnotationListener();
        
        $this->setExpectedException('BedRest\Events\Exception');
        $listeners = $driver->getListenersForClass($instance);
    }
}
