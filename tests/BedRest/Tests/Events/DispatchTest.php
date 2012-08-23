<?php

namespace BedRest\Tests\Events;

use BedRest\Events\Event;
use BedRest\Events\EventManager;
use BedRest\TestFixtures\Listener\AnnotationListener;
use BedRest\TestFixtures\Listener\StopPropagationListener;

/**
 * DispatchTest
 *
 * @author Geoff Adams <geoff@dianode.net>
 */
class DispatchTest extends \PHPUnit_Framework_TestCase
{
    public function testDispatchUnboundEvent()
    {
        $em = new EventManager();
        
        $instance = new AnnotationListener();
        
        $em->addListener('eventOne', array($instance, 'listenerOne'));
        
        $em->dispatch('unboundEvent', new Event());
    }
    
    public function testDispatchSingleClass()
    {
        $em = new EventManager();
        
        $instance = new AnnotationListener();
        
        $em->addListener('eventOne', array($instance, 'listenerOne'));
        
        $em->dispatch('eventOne', new Event());
        
        $this->assertTrue($instance->listenerOneCalled);
        $this->assertFalse($instance->listenerTwoCalled);
    }
    
    public function testDispatchMultipleClasses()
    {
        $em = new EventManager();
        
        $instanceOne = new AnnotationListener();
        
        $instanceTwo = new AnnotationListener();
        
        $em->addListeners('eventOne', array(
            array($instanceOne, 'listenerOne'),
            array($instanceTwo, 'listenerOne')
        ));
        
        $em->addListeners('eventTwo', array(
            array($instanceOne, 'listenerTwo'),
            array($instanceTwo, 'listenerTwo'),
        ));
        
        $em->dispatch('eventOne', new Event());
        
        $this->assertTrue($instanceOne->listenerOneCalled);
        $this->assertTrue($instanceTwo->listenerOneCalled);
        
        $this->assertFalse($instanceOne->listenerTwoCalled);
        $this->assertFalse($instanceTwo->listenerTwoCalled);
    }
    
    public function testStopPropagation()
    {
        $em = new EventManager();
        
        $instanceOne = new StopPropagationListener();
        $instanceTwo = new StopPropagationListener();
        
        $em->addListeners('stopPropagationEvent', array(
            array($instanceOne, 'listener'),
            array($instanceTwo, 'listener')
        ));
        
        $event = new Event();
        $this->assertFalse($event->propagationHalted());
        
        $em->dispatch('stopPropagationEvent', $event);
        
        $this->assertTrue($instanceOne->listenerCalled);
        $this->assertFalse($instanceTwo->listenerCalled);
        
        $this->assertTrue($event->propagationHalted());
    }
}
