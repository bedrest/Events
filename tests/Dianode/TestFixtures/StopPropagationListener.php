<?php

namespace Dianode\TestFixtures;

use Dianode\Events\Event;
use Dianode\Events\Annotations as Events;

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
     * @param \Dianode\Events\Event $event
     * 
     * @Events\Listener(event="stopPropagationEvent")
     */
    public function listener(Event $event)
    {
        $event->stopPropagation();
        
        $this->listenerCalled = true;
    }
}
