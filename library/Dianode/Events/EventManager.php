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
use Doctrine\Common\Annotations\Reader;

/**
 * EventManager
 *
 * @author Geoff Adams <geoff@dianode.net>
 */
class EventManager
{
    const ANNOTATION_LISTENER = 'Dianode\Events\Annotation\Listener';

    /**
     * Annotation reader instance.
     * 
     * @var Doctrine\Common\Annotations\Reader
     */
    protected $reader;
    
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
     * Takes an optional annotation reader to automate event listener binding from class annotations.
     * 
     * @param Doctrine\Common\Annotations\Reader $reader
     */
    public function __construct(Reader $reader = null)
    {
        $this->reader = $reader;
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
        $className = get_class($instance);
        
        if (!$this->reader) {
            throw Exception::noReader();
        }
        
        foreach ($this->readListenersForClass($className) as $event) {
            $this->addListener($event['event'], array($instance, $event['method']));
        }
    }
    
    /**
     * Adds a listener.
     * 
     * @param string $event
     * @param callable $observer
     */
    public function addListener($event, $observer)
    {
        if (!isset($this->listeners[$event])) {
            $this->listeners[$event] = array();
        }
        
        $this->listeners[$event][] = $observer;
    }
    
    /**
     * Adds a set of listeners.
     * 
     * @param string $event
     * @param callable $observers
     */
    public function addListeners($event, $observers)
    {
        foreach ($observers as $observer) {
            $this->addListener($event, $observer);
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
        
        foreach ($this->listeners[$event] as $observer) {
            call_user_func_array($observer, array($eventObject));
            
            if ($eventObject->propagationHalted()) {
                break;
            }
        }
    }
    
    /**
     * Returns an array of event listeners for a particular class.
     * @param string $className
     */
    protected function readListenersForClass($className)
    {
        $listeners = array();
        
        // do some reflection to get the methods
        $reflClass = new \ReflectionClass($className);
        $reflMethods = $reflClass->getMethods(\ReflectionMethod::IS_PUBLIC);

        // process each method's annotations
        foreach ($reflMethods as $reflMethod) {
            $annotations = $this->reader->getMethodAnnotations($reflMethod);

            foreach ($annotations as $annotation) {
                // get the listeners
                if (get_class($annotation) == self::ANNOTATION_LISTENER) {
                    $listeners[] = array(
                        'event' => $annotation->event,
                        'namespace' => $annotation->namespace,
                        'method' => $reflMethod->getName()
                    );
                }
            }
        }
        
        return $listeners;
    }
}