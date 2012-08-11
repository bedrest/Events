<?php

namespace Dianode\Tests\Events;

use Doctrine\Common\Annotations\AnnotationReader;
use Dianode\Events\Event;
use Dianode\Events\EventManager;
use Dianode\TestFixtures\ListenerClass;

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
    
    public function testAddClassListeners()
    {
        $reader = new AnnotationReader();
        $em = new EventManager($reader);
        
        $instance = new ListenerClass();
        
        $em->addClassListeners($instance);
        
        $this->assertCount(1, $em->getListeners('eventOne'));
        $this->assertCount(1, $em->getListeners('eventTwo'));
    }
    
    public function testDispatchNoListeners()
    {
        $reader = new AnnotationReader();
        $em = new EventManager($reader);
        
        $instance = new ListenerClass();
        
        $em->addClassListeners($instance);
        
        $em->dispatch('unboundEvent', new Event());
        
        $this->assertFalse($instance->listenerOneCalled);
        $this->assertFalse($instance->listenerTwoCalled);
    }
    
    public function testDispatchSingleClass()
    {
        $reader = new AnnotationReader();
        $em = new EventManager($reader);
        
        $instance = new ListenerClass();
        
        $em->addClassListeners($instance);
        
        $em->dispatch('eventOne', new Event());
        
        $this->assertTrue($instance->listenerOneCalled);
        $this->assertFalse($instance->listenerTwoCalled);
    }
}
