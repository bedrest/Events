<?php

namespace BedRest\TestFixtures\Listener;

use BedRest\Events\Annotations as Events;

/**
 * AnnotationListener
 *
 * @author Geoff Adams <geoff@dianode.net>
 */
class AnnotationListener
{
    public $listenerOneCalled = false;
    
    public $listenerTwoCalled = false;
    
    /**
     * @Events\Listener(event="eventOne")
     */
    public function listenerOne()
    {
        $this->listenerOneCalled = true;
    }
    
    /**
     * @Events\Listener(event="eventTwo")
     */
    public function listenerTwo()
    {
        $this->listenerTwoCalled = true;
    }
}
