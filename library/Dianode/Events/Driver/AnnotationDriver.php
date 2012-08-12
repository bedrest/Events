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

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Annotations\AnnotationReader;

/**
 * AnnotationDriver
 *
 * @author Geoff Adams <geoff@dianode.net>
 */
class AnnotationDriver implements Driver
{
    /**
     * Class name of the @Listener annotation.
     */
    const ANNOTATION_LISTENER = 'Dianode\Events\Annotations\Listener';
    
    /**
     * Reader instance.
     * 
     * @var \Doctrine\Common\Annotations\Reader $reader 
     */
    protected $reader;

    /**
     * Constructor.
     * 
     * Takes an optional Reader instance. If one isn't supplied, by default an instance of AnnotationReader is created.
     * 
     * @param \Doctrine\Common\Annotations\AnnotationReader $reader
     */
    public function __construct(Reader $reader = null)
    {
        if (!$reader) {
            $this->reader = new AnnotationReader();
        }
    }
    
    /**
     * {@inheritDoc}
     */
    public function getListenersForClass($className)
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
