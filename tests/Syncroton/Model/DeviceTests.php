<?php
/**
 * Syncroton
 *
 * @package     Syncroton
 * @subpackage  Tests
 * @license     http://www.tine20.org/licenses/lgpl.html LGPL Version 3
 * @copyright   Copyright (c) 2013-2013 Metaways Infosystems GmbH (http://www.metaways.de)
 * @author      Lars Kneschke <l.kneschke@metaways.de>
 */

/**
 * class to test Syncroton_Model_DeviceTests
 *
 * @package     Syncroton
 * @subpackage  Tests
 */
class Syncroton_Model_DeviceTests extends Syncroton_Model_ATestCase
{
    /**
     * test get version iPhone
     */
    public function testGetVersionIPhone()
    {
        $iPhone = Syncroton_Backend_DeviceTests::getTestDevice(Syncroton_Model_Device::TYPE_IPHONE);
        
        $this->assertEquals('1101', $iPhone->getMajorVersion());
    }
    
    /**
     * test get version BlackBerry 10
     */
    public function testGetVersionBlackBerry()
    {
        $blackBerry = Syncroton_Backend_DeviceTests::getTestDevice(Syncroton_Model_Device::TYPE_BLACKBERRY);
        
        $this->assertEquals('10.2.0.1443', $blackBerry->getMajorVersion());
    }
}
