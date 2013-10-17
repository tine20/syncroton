<?php
/**
 * Syncroton
 *
 * @package     Syncroton
 * @subpackage  Command
 * @license     http://www.tine20.org/licenses/lgpl.html LGPL Version 3
 * @copyright   Copyright (c) 2013-2013 Metaways Infosystems GmbH (http://www.metaways.de)
 * @author      Lars Kneschke <l.kneschke@metaways.de>
 */

/**
 * class to handle AutoDiscover command
 *
 * @package     Syncroton
 * @subpackage  Command
 */
class Syncroton_Command_AutoDiscover implements Syncroton_Command_ICommand
{
    /**
     * the domDocucment containing the xml request from the client
     *
     * @var DOMDocument
     */
    protected $requestBody;
    
    protected $emailAddress;
    
    public    $mobileSyncUrl;
    
    public    $certEnrollUrl;
    
    /**
     * constructor of this class
     * 
     * @param DOMDocument              $_requestBody
     * @param Syncroton_Model_IDevice  $_device
     * @param string                   $_policyKey
     */
    public function __construct($requestBody, Syncroton_Model_IDevice $device = null, $policyKey = null)
    {
        $this->requestBody = $requestBody;
    }
    
    /**
     * process the incoming data 
     */
    public function handle()
    {
        $xpath = new DomXPath($this->requestBody);
        $xpath->registerNamespace('2006', 'http://schemas.microsoft.com/exchange/autodiscover/mobilesync/requestschema/2006');
        
        $nodes = $xpath->query('//2006:Autodiscover/2006:Request/2006:EMailAddress');
        if ($nodes->length === 0) {
            throw new Syncroton_Exception();
        }
        
        $this->emailAddress = $nodes->item(0)->nodeValue;
    }
    
    /**
     * create the response
     * 
     * @return DOMDocument
     */
    public function getResponse()
    {
        // Creates an instance of the DOMImplementation class
        $imp = new DOMImplementation();
        
        // Creates a DOMDocument instance
        $document = $imp->createDocument("http://schemas.microsoft.com/exchange/autodiscover/mobilesync/requestschema/2006", 'Autodiscover');
        $document->xmlVersion   = '1.0';
        $document->encoding     = 'UTF-8';
        $document->formatOutput = false;
        
        $response = $document->documentElement->appendChild($document->createElement('Response'));
        
        $user = $response->appendChild($document->createElement('User'));
        $user->appendChild($document->createElement('EMailAddress', $this->emailAddress));
        
        $settings = $document->createElement('Settings');
        
        if (!empty($this->mobileSyncUrl)) {
            $server   = $document->createElement('Server');
            
            $server->appendChild($document->createElement('Type', 'MobileSync'));
            
            $server->appendChild($document->createElement('Url',  $this->mobileSyncUrl));
            $server->appendChild($document->createElement('Name', $this->mobileSyncUrl));
            
            $settings->appendChild($server);
        }
        
        if (!empty($this->certEnrollUrl)) {
            $server   = $document->createElement('Server');
            
            $server->appendChild($document->createElement('Type', 'CertEnroll'));
            
            $server->appendChild($document->createElement('Url',  $this->certEnrollUrl));
            $server->appendChild($document->createElement('Name'));
            $server->appendChild($document->createElement('ServerData', 'CertEnrollTemplate'));
            
            $settings->appendChild($server);
        }
        
        if ($settings->hasChildNodes()) {
            $action   = $response->appendChild($document->createElement('Action'));
            $action->appendChild($settings);
        }
        
        return $document;
    }
    
    /**
     * return headers of command
     * 
     * @return array list of headers
     */
    public function getHeaders()
    {
        return array(
            'Content-Type'  => 'text/xml;charset=utf-8'
        );
    }
}
