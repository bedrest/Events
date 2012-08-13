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

namespace Dianode\Events\Driver;

use Dianode\Events\EventSubscriber;
use Dianode\Events\Exception;

/**
 * InterfaceDriver
 *
 * @author Geoff Adams <geoff@dianode.net>
 */
class InterfaceDriver implements Driver
{
    /**
     * {@inheritDoc}
     */
    public function getListenersForClass($instance)
    {
        // ignore classes which don't implement the subscriber
        if (!$instance instanceof EventSubscriber) {
            return array();
        }
        
        // grab all the entries returned by getEventListeners() and parse them
        $listeners = array();
        
        foreach ($instance->getEventListeners() as $i => $item) {
            // check the definition is in a valid format
            if (!is_array($item)) {
                throw Exception::invalidListenerDefinition(get_class($instance));
            }
            
            // check all data is present
            if (!isset($item['event'])) {
                throw Exception::incompleteListenerDefinition(get_class($instance), 'missing event field');
            }
            
            if (!isset($item['method'])) {
                throw Exception::incompleteListenerDefinition(get_class($instance), 'missing method field');
            }
            
            // get the information
            $listener = array(
                'event' => $item['event'],
                'namespace' => null,
                'method' => $item['method']
            );
            
            if (isset($item['namespace'])) {
                $listener['namespace'] = $item['namespace'];
            }
            
            $listeners[] = $listener;
        }
        
        return $listeners;
    }
}

