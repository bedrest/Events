<?php

namespace BedRest\Tests\Events;

use BedRest\Events\Event;
use BedRest\Events\EventManager;
use BedRest\Events\Driver\AnnotationDriver;
use BedRest\TestFixtures\Listener\AnnotationListener;

/**
 * DriverTest
 *
 * @author Geoff Adams <geoff@dianode.net>
 */
class DriverTest extends \PHPUnit_Framework_TestCase
{
    public function testAddClassListenersWithAnnotations()
    {
        $reader = new AnnotationDriver();
        $em = new EventManager($reader);
        
        $instance = new AnnotationListener();
        
        $em->addClassListeners($instance);
        
        $this->assertCount(1, $em->getListeners('eventOne'));
        $this->assertCount(1, $em->getListeners('eventTwo'));
    }
    
    public function testDispatchNoListenersWithAnnotations()
    {
        $reader = new AnnotationDriver();
        $em = new EventManager($reader);
        
        $instance = new AnnotationListener();
        
        $em->addClassListeners($instance);
        
        $em->dispatch('unboundEvent', new Event());
        
        $this->assertFalse($instance->listenerOneCalled);
        $this->assertFalse($instance->listenerTwoCalled);
    }
    
    public function testDispatchSingleClassWithAnnotations()
    {
        $reader = new AnnotationDriver();
        $em = new EventManager($reader);
        
        $instance = new AnnotationListener();
        
        $em->addClassListeners($instance);
        
        $em->dispatch('eventOne', new Event());
        
        $this->assertTrue($instance->listenerOneCalled);
        $this->assertFalse($instance->listenerTwoCalled);
    }
    
    public function testDispatchMultipleClassesWithAnnotations()
    {
        $reader = new AnnotationDriver();
        $em = new EventManager($reader);
        
        $instanceOne = new AnnotationListener();
        $em->addClassListeners($instanceOne);
        
        $instanceTwo = new AnnotationListener();
        $em->addClassListeners($instanceTwo);
        
        $em->dispatch('eventOne', new Event());
        
        $this->assertTrue($instanceOne->listenerOneCalled);
        $this->assertTrue($instanceTwo->listenerOneCalled);
        
        $this->assertFalse($instanceOne->listenerTwoCalled);
        $this->assertFalse($instanceTwo->listenerTwoCalled);
    }
}
