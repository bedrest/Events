<?php

namespace BedRest\TestFixtures\Listener;

use BedRest\Events\Event;
use BedRest\Events\Annotations as Events;

/**
 * StopPropagationListener
 *
 * @author Geoff Adams <geoff@dianode.net>
 */
class StopPropagationListener
{
    public $listenerCalled = false;
    
    /**
     * 
     * @param \BedRest\Events\Event $event
     * 
     * @Events\Listener(event="stopPropagationEvent")
     */
    public function listener(Event $event)
    {
        $event->stopPropagation();
        
        $this->listenerCalled = true;
    }
}
