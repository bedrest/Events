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

use Dianode\Events\Exception;
use Dianode\Events\Driver\Driver;

/**
 * ChainedDriver
 *
 * @author Geoff Adams <geoff@dianode.net>
 */
class ChainedDriver implements Driver
{
    /**
     * Array of drivers to execute.
     * @var array
     */
    protected $drivers = array();
    
    /**
     * Constructor.
     * 
     * Takes an optional list of drivers to add to the chain.
     * 
     * @param array $drivers
     * @throws \Dianode\Events\Exception
     */
    public function __construct(array $drivers = array())
    {
        foreach ($drivers as $driver) {
            if (!$driver instanceof Driver) {
                if (is_object($driver)) {
                    throw Exception::invalidDriverSupplied(get_class($driver));
                } else {
                    throw Exception::invalidDriverSupplied(gettype($driver));
                }
            }
        }
        
        $this->drivers = $drivers;
    }
    
    /**
     * Adds a driver to the chain.
     * @param \Dianode\Events\Driver\Driver $driver
     */
    public function addDriver(Driver $driver)
    {
        $this->drivers[] = $driver;
    }
    
    /**
     * Returns all drivers associated with this chained driver.
     * @return array
     */
    public function getDrivers()
    {
        return $this->drivers;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getListenersForClass($instance)
    {
        $listeners = array();
        
        foreach ($this->drivers as $driver) {
            $listeners = array_merge($listeners, $driver->getListenersForClass($instance));
        }
        
        return $listeners;
    }
}
