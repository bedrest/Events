<?php

namespace Dianode\TestFixtures;

use Dianode\Events\Annotations as Events;

/**
 * ListenerClass
 *
 * @author Geoff Adams <geoff@dianode.net>
 */
class ListenerClass
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
