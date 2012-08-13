<?php

namespace Dianode\TestFixtures\Listener;

use Dianode\Events\EventSubscriber;

/**
 * IncompleteInterfaceListener
 *
 * @author Geoff Adams <geoff@dianode.net>
 */
class IncompleteInterfaceListener implements EventSubscriber
{
    protected $fieldToSkip;
    
    public function __construct($fieldToSkip)
    {
        $this->fieldToSkip = $fieldToSkip;
    }
    
    public function getEventListeners()
    {
        if ($this->fieldToSkip == 'method') {
            return array(
                array('event' => 'eventOne', 'method' => 'listenerOne'),
                array('event' => 'eventTwo')
            );
        } elseif ($this->fieldToSkip) {
            return array(
                array('event' => 'eventOne', 'method' => 'listenerOne'),
                array('method' => 'listenerTwo')
            );
        }
        
        throw new \Exception('Invalid fieldToSkip definition');
    }
}
