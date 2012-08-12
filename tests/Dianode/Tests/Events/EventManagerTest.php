<?php

namespace Dianode\Tests\Events;

use Dianode\Events\Event;
use Dianode\Events\EventManager;
use Dianode\Events\Driver\AnnotationDriver;
use Dianode\TestFixtures\BasicListener;
use Dianode\TestFixtures\StopPropagationListener;

/**
 * EventManagerTest
 *
 * @author Geoff Adams <geoff@dianode.net>
 */
class EventManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testUnboundEvent()
    {
        $em = new EventManager();
        
        $this->assertEmpty($em->getListeners('unboundEvent'));
    }
    
    public function testAddListener()
    {
        $em = new EventManager();
        
        $em->addListener('boundEvent', array($this, 'listener'));
        
        $this->assertCount(1, $em->getListeners('boundEvent'));
    }
    
    public function testAddListeners()
    {
        $em = new EventManager();
        
        $listeners = array(
            array($this, 'listenerOne'),
            array($this, 'listenerTwo'),
            array(new \stdClass(), 'listenerThree'),
        );
        
        $em->addListeners('boundEvent', $listeners);
        
        $this->assertCount(3, $em->getListeners('boundEvent'));
    }
    
    public function testAddClassListenersWithNoReader()
    {
        $em = new EventManager();
        
        $this->setExpectedException('Dianode\Events\Exception');
        
        $em->addClassListeners(new \stdClass());
    }
    
    public function testAddClassListenersWithAnnotations()
    {
        $reader = new AnnotationDriver();
        $em = new EventManager($reader);
        
        $instance = new BasicListener();
        
        $em->addClassListeners($instance);
        
        $this->assertCount(1, $em->getListeners('eventOne'));
        $this->assertCount(1, $em->getListeners('eventTwo'));
    }
    
    public function testDispatchNoListenersWithAnnotations()
    {
        $reader = new AnnotationDriver();
        $em = new EventManager($reader);
        
        $instance = new BasicListener();
        
        $em->addClassListeners($instance);
        
        $em->dispatch('unboundEvent', new Event());
        
        $this->assertFalse($instance->listenerOneCalled);
        $this->assertFalse($instance->listenerTwoCalled);
    }
    
    public function testDispatchSingleClass()
    {
        $em = new EventManager();
        
        $instance = new BasicListener();
        
        $em->addListener('eventOne', array($instance, 'listenerOne'));
        
        $em->dispatch('eventOne', new Event());
        
        $this->assertTrue($instance->listenerOneCalled);
        $this->assertFalse($instance->listenerTwoCalled);
    }
    
    public function testDispatchSingleClassWithAnnotations()
    {
        $reader = new AnnotationDriver();
        $em = new EventManager($reader);
        
        $instance = new BasicListener();
        
        $em->addClassListeners($instance);
        
        $em->dispatch('eventOne', new Event());
        
        $this->assertTrue($instance->listenerOneCalled);
        $this->assertFalse($instance->listenerTwoCalled);
    }
    
    public function testDispatchMultipleClasses()
    {
        $em = new EventManager();
        
        $instanceOne = new BasicListener();
        
        $instanceTwo = new BasicListener();
        
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
    
    public function testDispatchMultipleClassesWithAnnotations()
    {
        $reader = new AnnotationDriver();
        $em = new EventManager($reader);
        
        $instanceOne = new BasicListener();
        $em->addClassListeners($instanceOne);
        
        $instanceTwo = new BasicListener();
        $em->addClassListeners($instanceTwo);
        
        $em->dispatch('eventOne', new Event());
        
        $this->assertTrue($instanceOne->listenerOneCalled);
        $this->assertTrue($instanceTwo->listenerOneCalled);
        
        $this->assertFalse($instanceOne->listenerTwoCalled);
        $this->assertFalse($instanceTwo->listenerTwoCalled);
    }
    
    public function testStopPropagation()
    {
        $reader = new AnnotationDriver();
        $em = new EventManager($reader);
        
        $instanceOne = new StopPropagationListener();
        $instanceTwo = new StopPropagationListener();
        
        $em->addClassListeners($instanceOne);
        $em->addClassListeners($instanceTwo);
        
        $event = new Event();
        $this->assertFalse($event->propagationHalted());
        
        $em->dispatch('stopPropagationEvent', $event);
        
        $this->assertTrue($instanceOne->listenerCalled);
        $this->assertFalse($instanceTwo->listenerCalled);
        
        $this->assertTrue($event->propagationHalted());
    }
}
