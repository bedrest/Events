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

/**
 * Exception
 *
 * @author Geoff Adams <geoff@dianode.net>
 */
class Exception extends \Exception
{
    /**
     * Exception for when the EventManager has no driver instance.
     * @return \Dianode\Events\Exception
     */
    public static function noDriver()
    {
        return new self("No driver has been supplied.");
    }
    
    /**
     * Exception for when a listener definition is invalid.
     * @param string $className
     * @return \Dianode\Events\Exception
     */
    public static function invalidListenerDefinition($className)
    {
        return new self("An event definition is invalid in '{$className}'.");
    }
    
    /**
     * Exception for when a listener definition is incomplete.
     * @param string $className
     * @return \Dianode\Events\Exception
     */
    public static function incompleteListenerDefinition($className, $reason = null)
    {
        $msg = "An event definition is incomplete or invalid in '{$className}'.";
        
        if ($reason) {
            $msg .= ": $reason";
        }
        
        return new self($msg);
    }
    
    /**
     * Exception for when an invalid driver is supplied.
     * @param string $className
     * @return \Dianode\Events\Exception
     */
    public static function invalidDriverSupplied($className)
    {
        return new self("An invalid driver was supplied: '{$className}'.");
    }
}

