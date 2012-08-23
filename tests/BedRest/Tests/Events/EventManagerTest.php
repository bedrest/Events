<?php

namespace BedRest\Tests\Events;

use BedRest\Events\EventManager;

/**
 * EventManagerTest
 *
 * @author Geoff Adams <geoff@dianode.net>
 */
class EventManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiateWithInvalidDriver()
    {
        // constructor should not allow instantiation with a non-driver
        $this->setExpectedException('PHPUnit_Framework_Error');
        
        $em = new EventManager(new stdClass());
    }
    
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
        
        $this->setExpectedException('BedRest\Events\Exception');
        
        $em->addClassListeners(new \stdClass());
    }
}
