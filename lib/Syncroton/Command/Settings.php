<?php
/**
 * Syncroton
 *
 * @package     Syncroton
 * @subpackage  Command
 * @license     http://www.tine20.org/licenses/lgpl.html LGPL Version 3
 * @copyright   Copyright (c) 2008-2012 Metaways Infosystems GmbH (http://www.metaways.de)
 * @author      Lars Kneschke <l.kneschke@metaways.de>
 */

/**
 * class to handle ActiveSync Settings command
 *
 * @package     Syncroton
 * @subpackage  Command
 */
class Syncroton_Command_Settings extends Syncroton_Command_Wbxml 
{
    const STATUS_SUCCESS = 1;
    
    protected $_defaultNameSpace = 'uri:Settings';
    protected $_documentElement  = 'Settings';
    
    /**
     * @var Syncroton_Model_DeviceInformation
     */
    protected $_deviceInformation;

    protected $_userInformationRequested = false;
    protected $_OofGet;
    protected $_OofSet;
    
    
    /**
     * process the XML file and add, change, delete or fetches data 
     *
     */
    public function handle()
    {
        $xml = simplexml_import_dom($this->_requestBody);
        
        if(isset($xml->DeviceInformation->Set)) {
            $this->_deviceInformation = new Syncroton_Model_DeviceInformation($xml->DeviceInformation->Set);
            
            $this->_device->model           = $this->_deviceInformation->model;
            $this->_device->imei            = $this->_deviceInformation->iMEI;
            $this->_device->friendlyname    = $this->_deviceInformation->friendlyName;
            $this->_device->os              = $this->_deviceInformation->oS;
            $this->_device->oslanguage      = $this->_deviceInformation->oSLanguage;
            $this->_device->phonenumber     = $this->_deviceInformation->phoneNumber;

            if ($this->_device->isDirty()) {
                $this->_device = $this->_deviceBackend->update($this->_device);
            }
        }
        
        if(isset($xml->UserInformation->Get)) {
            $this->_userInformationRequested = true;
        }

        if (isset($xml->Oof)) {
            if (isset($xml->Oof->Get)) {
                $this->_OofGet = array('bodyType' => $xml->Oof->Get->BodyType);
            } else if (isset($xml->Oof->Set)) {
                $this->_OofSet = new Syncroton_Model_Oof($xml->Oof->Set);
            }
        }
    }
    
    /**
     * this function generates the response for the client
     *
     */
    public function getResponse()
    {
        $settings = $this->_outputDom->documentElement;
        
        $settings->appendChild($this->_outputDom->createElementNS('uri:Settings', 'Status', self::STATUS_SUCCESS));
        
        if ($this->_deviceInformation instanceof Syncroton_Model_DeviceInformation) {
            $deviceInformation = $settings->appendChild($this->_outputDom->createElementNS('uri:Settings', 'DeviceInformation'));
            $set = $deviceInformation->appendChild($this->_outputDom->createElementNS('uri:Settings', 'Set'));
            $set->appendChild($this->_outputDom->createElementNS('uri:Settings', 'Status', self::STATUS_SUCCESS));
        }
        
        if($this->_userInformationRequested === true) {
            $smtpAddresses = array();
            
            $userInformation = $settings->appendChild($this->_outputDom->createElementNS('uri:Settings', 'UserInformation'));
            $userInformation->appendChild($this->_outputDom->createElementNS('uri:Settings', 'Status', self::STATUS_SUCCESS));
            $get = $userInformation->appendChild($this->_outputDom->createElementNS('uri:Settings', 'Get'));
            if(!empty($smtpAddresses)) {
                $emailAddresses = $get->appendChild($this->_outputDom->createElementNS('uri:Settings', 'EmailAddresses'));
                foreach($smtpAddresses as $smtpAddress) {
                    $emailAddresses->appendChild($this->_outputDom->createElementNS('uri:Settings', 'SMTPAddress', $smtpAddress));
                }
            }
        }

        // Out-of-Office
        if (!empty($this->_OofGet)) {
            try {
                $OofGet = $this->_deviceBackend->getOOF($this->_OofGet);
            } catch (Exception $e) {
                if ($e instanceof Syncroton_Exception_Status) {
                    $OofStatus = $e->getCode();
                } else {
                    $OofStatus = Syncroton_Exception_Status::SERVER_ERROR;
                }

                if ($this->_logger instanceof Zend_Log) {
                    $this->_logger->warn(__METHOD__ . '::' . __LINE__ . " Setting OOF failed: " . $e->getMessage());
                }
            }

            // expected empty result if OOF is not supported by the server
            if ($OofGet instanceof Syncroton_Model_Oof) {
                $Oof = $settings->appendChild($this->_outputDom->createElementNS('uri:Settings', 'Oof'));
                $Oof->appendChild($this->_outputDom->createElementNS('uri:Settings', 'Status', $OofStatus));
                $Get = $Oof->appendChild($this->_outputDom->createElementNS('uri:Settings', 'Get'));
                $OofGet->appendXML($Get, $this->_device);
            }
        } else if (!empty($this->_OofSet)) {
            try {
                $this->_deviceBackend->setOOF($this->_OofSet);
                $OofStatus = self::STATUS_SUCCESS;
            } catch (Exception $e) {
                if ($e instanceof Syncroton_Exception_Status) {
                    $OofStatus = $e->getCode();
                } else {
                    $OofStatus = Syncroton_Exception_Status::SERVER_ERROR;
                }

                if ($this->_logger instanceof Zend_Log) {
                    $this->_logger->warn(__METHOD__ . '::' . __LINE__ . " Setting OOF failed: " . $e->getMessage());
                }
            }

            $Oof = $settings->appendChild($this->_outputDom->createElementNS('uri:Settings', 'Oof'));
            $Oof->appendChild($this->_outputDom->createElementNS('uri:Settings', 'Status', $OofStatus));
        }

        return $this->_outputDom;
    }
}
