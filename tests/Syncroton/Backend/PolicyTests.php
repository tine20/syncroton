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
class Syncroton_Backend_PolicyTests extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Syncroton_Backend_Policy
     */
    protected $_policyBackend;
    
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
        
        $this->_policyBackend = new Syncroton_Backend_Policy($this->_db);
        
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
    public function testCreatePolicy()
    {
        $newPolicy = new Syncroton_Model_Policy(array(
            'description'           => 'description',
            'policyKey'             => '28364654',
            'name'                  => 'PHPUNIT policy',
            'allowBluetooth'        => null,
            'allowSMIMEEncryptionAlgorithmNegotiation' => 0,
            'devicePasswordEnabled' => 1,
        ));
        
        $policy = $this->_policyBackend->create($newPolicy);
        
        $this->assertEquals('description', $policy->description);
        $this->assertEquals(null, $policy->allowBluetooth);
        $this->assertEquals(0, $policy->allowSMIMEEncryptionAlgorithmNegotiation);
        $this->assertEquals(1, $policy->devicePasswordEnabled);
        
        return $policy;
    }
    
    public function testDeletePolicy()
    {
        $policy = $this->testCreatePolicy();
        
        $result = $this->_policyBackend->delete($policy);

        $this->assertTrue($result);
    }
    
    public function testGetExceptionNotFound()
    {
        $this->expectException('Syncroton_Exception_NotFound');
    
        $this->_policyBackend->get('invalidId');
    }
}
