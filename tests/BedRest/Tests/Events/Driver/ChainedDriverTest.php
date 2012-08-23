<?php

namespace BedRest\Tests\Events\Driver;

use BedRest\Events\Driver\ChainedDriver;
use BedRest\Events\Driver\InterfaceDriver;

/**
 * ChainedDriverTest
 *
 * @author Geoff Adams <geoff@dianode.net>
 */
class ChainedDriverTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiateWithInvalidParameter()
    {
        $this->setExpectedException('PHPUnit_Framework_Error');
        $driver = new ChainedDriver('invalid');
    }
    
    public function testInstantiateWithNonObjectDriver()
    {
        $interfaceDriver = new InterfaceDriver();
        
        $this->setExpectedException('BedRest\Events\Exception');
        $driver = new ChainedDriver(array(
            $interfaceDriver,
            false
        ));
    }
    
    public function testInstantiateWithInvalidDriver()
    {
        $interfaceDriver = new InterfaceDriver();
        
        $this->setExpectedException('BedRest\Events\Exception');
        $driver = new ChainedDriver(array(
            $interfaceDriver,
            new \stdClass()
        ));
    }
    
    public function testAddInvalidDriver()
    {
        $driver = new ChainedDriver();
        
        $this->setExpectedException('PHPUnit_Framework_Error');
        $driver->addDriver(false);
    }
    
    public function testAddDriver()
    {
        $interfaceDriver = new InterfaceDriver();
        
        $driver = new ChainedDriver();
        $driver->addDriver($interfaceDriver);
        
        $boundDrivers = $driver->getDrivers();
        $this->assertInternalType('array', $boundDrivers);
        $this->assertCount(1, $boundDrivers);
        $this->assertEquals($interfaceDriver, $boundDrivers[0]);
    }
}
