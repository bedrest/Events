<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

namespace Dianode\Events;

use Dianode\Events\Event;
use Dianode\Events\Driver\Driver;
use Doctrine\Common\Annotations\Reader;

/**
 * EventManager
 *
 * @author Geoff Adams <geoff@dianode.net>
 */
class EventManager
{
    /**
     * List of event listeners.
     * 
     * @var array
     */
    protected $listeners = array();
    
    /**
     * Mapping driver for automatically adding listeners based on a class name.
     * 
     * @var \Dianode\Events\Driver\Driver
     */
    protected $driver;

    /**
     * Constructor.
     * 
     * Takes an optional driver to automate event listener binding from class annotations.
     * 
     * @param \Dianode\Events\Driver\Driver $driver
     */
    public function __construct(Driver $driver = null)
    {
        $this->driver = $driver;
    }
    
    /**
     * Automatically maps event listeners associated with a class using the driver provided to this instance and 
     * registers them.
     * 
     * @param object $instance
     * @throws \Dianode\Events\MappingException
     */
    public function addClassListeners($instance)
    {
        if (!$this->driver) {
            throw Exception::noDriver();
        }
        
        foreach ($this->driver->getListenersForClass($instance) as $listener) {
            $this->addListener($listener['event'], array($instance, $listener['method']));
        }
    }
    
    /**
     * Adds a listener.
     * 
     * @param string $event
     * @param callable $listener
     */
    public function addListener($event, $listener)
    {
        if (!isset($this->listeners[$event])) {
            $this->listeners[$event] = array();
        }
        
        $this->listeners[$event][] = $listener;
    }
    
    /**
     * Adds a set of listeners.
     * 
     * @param string $event
     * @param callable $listeners
     */
    public function addListeners($event, $listeners)
    {
        foreach ($listeners as $listener) {
            $this->addListener($event, $listener);
        }
    }
    
    /**
     * Retrieves all listeners for an event.
     * 
     * @param string $event
     * @return array
     */
    public function getListeners($event)
    {
        if (!isset($this->listeners[$event]) || !is_array($this->listeners[$event])) {
            return array();
        }
        
        return $this->listeners[$event];
    }
    
    /**
     * Dispatches an event to all listeners.
     * 
     * @param string $event
     * @param \Dianode\Events\Event $eventObject
     */
    public function dispatch($event, Event $eventObject)
    {
        if (!isset($this->listeners[$event])) {
            return;
        }
        
        foreach ($this->listeners[$event] as $listener) {
            call_user_func_array($listener, array($eventObject));
            
            if ($eventObject->propagationHalted()) {
                break;
            }
        }
    }
}
