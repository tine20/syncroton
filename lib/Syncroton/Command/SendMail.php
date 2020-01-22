<?php
/**
 * Syncroton
 *
 * @package     Syncroton
 * @subpackage  Command
 * @license     http://www.tine20.org/licenses/lgpl.html LGPL Version 3
 * @copyright   Copyright (c) 2009-2012 Metaways Infosystems GmbH (http://www.metaways.de)
 * @copyright   Copyright (c) 2009-2016 Kolab Systems AG (http://kolabsystems.com)
 * @author      Lars Kneschke <l.kneschke@metaways.de>
 * @author      Aleksander Machniak <machniak@kolabsystems.com>
 */

/**
 * class to handle ActiveSync SendMail command
 *
 * @package     Syncroton
 * @subpackage  Command
 */
class Syncroton_Command_SendMail extends Syncroton_Command_Wbxml
{
    protected $_defaultNameSpace    = 'uri:ComposeMail';
    protected $_documentElement     = 'SendMail';

    protected $_mime;
    protected $_saveInSent;
    protected $_source;
    protected $_replaceMime = false;

    /**
     * Process the XML file and add, change, delete or fetches data
     */
    public function handle()
    {
        if (isset($this->_requestParameters['contentType']) &&
                $this->_requestParameters['contentType'] === 'message/rfc822') {
            $this->_mime          = $this->_requestBody;
            $this->_saveInSent    = $this->_requestParameters['saveInSent'];
            $this->_replaceMime   = false;

            $this->_source = array(
                'collectionId' => $this->_requestParameters['collectionId'],
                'itemId'       => $this->_requestParameters['itemId'],
                'instanceId'   => null
            );

        } else if ($this->_requestBody) {
            $xml = simplexml_import_dom($this->_requestBody);

            $this->_mime          = (string) $xml->Mime;
            $this->_saveInSent    = isset($xml->SaveInSentItems);
            $this->_replaceMime   = isset($xml->ReplaceMime);

            if (isset ($xml->Source)) {
                if ($xml->Source->LongId) {
                    $this->_source = (string)$xml->Source->LongId;
                } else {
                    $this->_source = array(
                        'collectionId' => (string)$xml->Source->FolderId,
                        'itemId'       => (string)$xml->Source->ItemId,
                        'instanceId'   => isset($xml->Source->InstanceId) ? (string)$xml->Source->InstanceId : null
                    );
                }
            }
        }

        if (empty($this->_mime)) {
            if ($this->_logger instanceof Zend_Log)
                $this->_logger->warn(__METHOD__ . '::' . __LINE__ . " Sending email failed: Empty input");


            if (version_compare($this->_device->acsversion, '14.0', '<')) {
                header("HTTP/1.1 400 Invalid content");
                die;
            }

            $response_type = 'Syncroton_Model_' . $this->_documentElement;
            $response      = new $response_type(array(
                'status' => Syncroton_Exception_Status::INVALID_CONTENT,
            ));

            $response->appendXML($this->_outputDom->documentElement, $this->_device);

            return $this->_outputDom;
        }


        if ($this->_logger instanceof Zend_Log)
            $this->_logger->debug(__METHOD__ . '::' . __LINE__ . " saveInSent: " . (int)$this->_saveInSent);
    }

    /**
     * this function generates the response for the client
     *
     * @return void|DOMDocument
     */
    public function getResponse()
    {
        $dataController = Syncroton_Data_Factory::factory(Syncroton_Data_Factory::CLASS_EMAIL, $this->_device, $this->_syncTimeStamp);

        try {
            $this->sendMail($dataController);
        } catch (Syncroton_Exception_Status $ses) {
            if ($this->_logger instanceof Zend_Log)
                $this->_logger->warn(__METHOD__ . '::' . __LINE__ . " Sending email failed: " . $ses->getMessage());

            $response_type = 'Syncroton_Model_' . $this->_documentElement;
            $response      = new $response_type(array(
                'status' => $ses->getCode(),
            ));

            $response->appendXML($this->_outputDom->documentElement, $this->_device);

            return $this->_outputDom;
        }
    }

    /**
     * Execute email sending method of data controller
     * To be overwritten by SmartForward and SmartReply command handlers
     */
    protected function sendMail($dataController)
    {
        $dataController->sendEmail($this->_mime, $this->_saveInSent);
    }
}
