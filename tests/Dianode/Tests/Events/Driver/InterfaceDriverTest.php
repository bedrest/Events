<?php

namespace Dianode\Tests\Events\Driver;

use Dianode\Events\Driver\InterfaceDriver;
use Dianode\TestFixtures\Listener\AnnotationListener;
use Dianode\TestFixtures\Listener\InterfaceListener;
use Dianode\TestFixtures\Listener\IncompleteInterfaceListener;
use Dianode\TestFixtures\Listener\InvalidInterfaceListener;

/**
 * InterfaceDriverTest
 *
 * @author Geoff Adams <geoff@dianode.net>
 */
class InterfaceDriverTest extends \PHPUnit_Framework_TestCase
{
    public function testGetListeners()
    {
        $driver = new InterfaceDriver();
        
        $instance = new InterfaceListener();
        
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
    
    public function testGetListenersForNonInterfaceClass()
    {
        $driver = new InterfaceDriver();
        
        $instance = new AnnotationListener();
        
        $listeners = $driver->getListenersForClass($instance);
        
        $this->assertInternalType('array', $listeners);
        $this->assertEmpty($listeners);
    }
    
    public function testGetListenersInvalidDefinition()
    {
        $driver = new InterfaceDriver();
        
        $instance = new InvalidInterfaceListener();
        
        $this->setExpectedException('Dianode\Events\Exception');
        $listeners = $driver->getListenersForClass($instance);
    }
    
    public function testGetListenersIncompleteDefinitionMissingEvent()
    {
        $driver = new InterfaceDriver();
        
        $instance = new IncompleteInterfaceListener('event');
        
        $this->setExpectedException('Dianode\Events\Exception');
        $listeners = $driver->getListenersForClass($instance);
    }
    
    public function testGetListenersIncompleteDefinitionMissingMethod()
    {
        $driver = new InterfaceDriver();
        
        $instance = new IncompleteInterfaceListener('method');
        
        $this->setExpectedException('Dianode\Events\Exception');
        $listeners = $driver->getListenersForClass($instance);
    }
}
