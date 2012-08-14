<?php

namespace Dianode\Tests\Events;

use Dianode\Events\Event;
use Dianode\Events\EventManager;

/**
 * ClosureDispatchTest
 *
 * @author Geoff Adams <geoff@dianode.net>
 */
class ClosureDispatchTest extends \PHPUnit_Framework_TestCase
{
    public function testAddEventListener()
    {
        $em = new EventManager();
        
        $listener = function(Event $event) {
        };
        
        $em->addListener('event', $listener);
        
        $listeners = $em->getListeners('event');
        
        $this->assertInternalType('array', $listeners);
        $this->assertCount(1, $listeners);
        
        $this->assertEquals($listener, $listeners[0]);
    }
    
    public function testDispatchClosureListener()
    {
        $em = new EventManager();
        
        $listenerCalled = false;
        $listener = function(Event $event) use (&$listenerCalled) {
            $listenerCalled = true;
        };
        
        $em->addListener('event', $listener);
        
        $em->dispatch('event', new Event());
        
        $this->assertTrue($listenerCalled);
    }
}
