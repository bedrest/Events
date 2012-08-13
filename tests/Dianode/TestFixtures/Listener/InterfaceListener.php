<?php

namespace Dianode\TestFixtures\Listener;

use Dianode\Events\EventSubscriber;

/**
 * InterfaceListener
 *
 * @author Geoff Adams <geoff@dianode.net>
 */
class InterfaceListener implements EventSubscriber
{
    public function listenerOne(Event $event)
    {
        
    }
    
    public function listenerTwo(Event $event)
    {
        
    }
    
    public function getEventListeners()
    {
        return array(
            array('event' => 'eventOne', 'method' => 'listenerOne'),
            array('event' => 'eventTwo', 'method' => 'listenerTwo')
        );
    }
}
