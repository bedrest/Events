<?php

namespace Dianode\TestFixtures\Listener;

use Dianode\Events\Annotations as Events;

/**
 * IncompleteAnnotationListener
 *
 * @author Geoff Adams <geoff@dianode.net>
 */
class IncompleteAnnotationListener
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
     * @Events\Listener()
     */
    public function listenerTwo()
    {
        $this->listenerTwoCalled = true;
    }
}
