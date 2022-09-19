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
class Syncroton_Command_GetAttachmentTests extends Syncroton_Command_ATestCase
{
    #protected $_logPriority = Zend_Log::DEBUG;
    
    /**
     * 
     */
    public function testGetAttachment()
    {
        $getAttachment = new Syncroton_Command_GetAttachment(null, $this->_device, array('attachmentName'  => 'FooBar' . Syncroton_Data_AData::LONGID_DELIMITER . '1', 'policyKey' => null));
        
        $getAttachment->handle();
        
        ob_start();
        
        $responseDoc = $getAttachment->getResponse();

        $result = ob_get_contents();
        ob_end_clean();
        
        $this->assertEquals('Lars', $result);
    }
}
