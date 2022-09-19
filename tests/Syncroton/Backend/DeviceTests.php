<?php
/**
 * Syncroton
 *
 * @package     Syncroton
 * @subpackage  Tests
 * @license     http://www.tine20.org/licenses/lgpl.html LGPL Version 3
 * @copyright   Copyright (c) 2009-2022 Metaways Infosystems GmbH (http://www.metaways.de)
 * @author      Lars Kneschke <l.kneschke@metaways.de>
 */

/**
 * class to test <...>
 *
 * @package     Syncroton
 * @subpackage  Tests
 */
class Syncroton_Backend_DeviceTests extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Syncroton_Backend_Device
     */
    protected $_deviceBackend;
    
    /**
     * @var Zend_Db_Adapter_Abstract
     */
    protected $_db;

    
    /**
     * (non-PHPdoc)
     * @see ActiveSync/ActiveSync_TestCase::setUp()
     */
    protected function setUp(): void
    {
        $this->_db = getTestDatabase();
        
        $this->_db->beginTransaction();
        
        $this->_deviceBackend = new Syncroton_Backend_Device($this->_db);
        
    }

    /**
     * Tears down the fixture
     * This method is called after a test is executed.
     *
     * @access protected
     */
    protected function tearDown(): void
    {
        $this->_db->rollBack();
    }
    
    /**
     * test sync with non existing collection id
     */
    public function testCreateDevice()
    {
        $newDevice = Syncroton_Backend_DeviceTests::getTestDevice();
        
        $device = $this->_deviceBackend->create($newDevice);
        
        //var_dump($device);
        
        $this->assertEquals($newDevice->ownerId, $device->ownerId);
        
        return $device;
    }
    
    public function testDeleteDevice()
    {
        $device = $this->testCreateDevice();
        
        $result = $this->_deviceBackend->delete($device);

        $this->assertTrue($result);
    }
    
    public function testGetExceptionNotFound()
    {
        $this->expectException('Syncroton_Exception_NotFound');
    
        $this->_deviceBackend->get('invalidId');
    }
    
    public function testGetUserDevice()
    {
        $device = $this->testCreateDevice();
        
        $userDevice = $this->_deviceBackend->getUserDevice('1234', 'iphone-abcd');
        
        $this->assertEquals($device->id, $userDevice->id);
        
        $this->expectException('Syncroton_Exception_NotFound');
        
        $userDevice = $this->_deviceBackend->getUserDevice('1234', 'iphone-xyz');
    }
    
    /**
     * valiadte isDirty of model
     */
    public function testIsDirty()
    {
        $device = $this->getTestDevice(Syncroton_Model_Device::TYPE_ANDROID);
        
        $this->assertEquals(false, $device->isDirty(), 'initial value mismatch');
        
        $device->remotewipe = 0;
        $device->acsversion = '12.0';
        
        $this->assertEquals(false, $device->isDirty(), 'dirty should be false, after update with same values');
        
        $device->acsversion = '14.1';
        
        $this->assertEquals(true, $device->isDirty());
    }
    
    /**
     * 
     * @return Syncroton_Model_Device
     */
    public static function getTestDevice($_type = null)
    {
        switch($_type) {
            case Syncroton_Model_Device::TYPE_ANDROID:
                $device = new Syncroton_Model_Device(array(
                    'deviceid'   => 'android-abcd',
                    'devicetype' => Syncroton_Model_Device::TYPE_ANDROID,
                    'policykey'  => null,
                    'policyId'   => null,
                    'ownerId'    => '1234',
                    'useragent'  => 'blabla',
                    'acsversion' => '12.0',
                    'remotewipe' => 0
                )); 
                break;
            
            case Syncroton_Model_Device::TYPE_BLACKBERRY:
                $device = new Syncroton_Model_Device(array(
                    'deviceid'   => 'BB2B2449CA',
                    'devicetype' => Syncroton_Model_Device::TYPE_BLACKBERRY,
                    'policykey'  => null,
                    'policyId'   => null,
                    'ownerId'    => '1234',
                    'useragent'  => 'RIM-Q10-SQN100-3/10.2.0.1443',
                    'acsversion' => '14.1',
                    'remotewipe' => 0
                )); 
                break;
            
            case Syncroton_Model_Device::TYPE_WEBOS:
                $device = new Syncroton_Model_Device(array(
                    'deviceid'   => 'webos-abcd',
                    'devicetype' => Syncroton_Model_Device::TYPE_ANDROID,
                    'policykey'  => null,
                    'policyId'   => null,
                    'ownerId'    => '1234',
                    'useragent'  => 'blabla',
                    'acsversion' => '12.0',
                    'remotewipe' => 0
                )); 
                break;
            
            case Syncroton_Model_Device::TYPE_IPHONE:
            default:
                $device = new Syncroton_Model_Device(array(
                    'deviceid'   => 'iphone-abcd',
                    'devicetype' => Syncroton_Model_Device::TYPE_IPHONE,
                    'policykey'  => null,
                    'policyId'   => null,
                    'ownerId'    => '1234',
                    'useragent'  => 'Apple-iPhone3C1/1101.465',
                    'acsversion' => '12.1',
                    'remotewipe' => 0
                )); 
                break;
        }

        return $device; 
    }
}
