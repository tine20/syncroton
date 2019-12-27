<?php
/**
 * Syncroton
 *
 * @package     Syncroton
 * @subpackage  Model
 * @license     http://www.tine20.org/licenses/lgpl.html LGPL Version 3
 * @copyright   Copyright (c) 2012-2014 Kolab Systems AG
 * @author      Aleksander Machniak <machniak@kolabsys.com>
 */

/**
 * class to handle (Settings/UserInformation/Get/Accounts/) Account element
 *
 * @package     Syncroton
 * @subpackage  Model
 * @property    string  accountId
 * @property    string  accountName
 * @property    string  userDisplayName
 * @property    bool    sendDisabled
 * @property    string  primaryAddress
 * @property    array   addresses
 */
class Syncroton_Model_Account extends Syncroton_Model_AXMLEntry
{
    protected $_xmlBaseElement = 'Account';

    protected $_properties = array(
        'Settings' => array(
            'accountId'       => array('type' => 'string'),
            'accountName'     => array('type' => 'string'),
            'userDisplayName' => array('type' => 'string'),
            'sendDisabled'    => array('type' => 'number'),
//            'emailAddresses'  => array('type' => 'container'),
        ),
        'Internal' => array(
            'primaryAddress' => array('type' => 'string'),
            'addresses'      => array('type' => 'array'),
        ),
    );

    /**
     * (non-PHPdoc)
     * @see Syncroton_Model_AXMLEntry::appendXML()
     */
    public function appendXML(DOMElement $_domParent, Syncroton_Model_IDevice $device)
    {
        parent::appendXML($_domParent, $device);

        $nameSpace = 'uri:Settings';
        $document  = $_domParent->ownerDocument;

        // handle EmailAddresses element
        $list = $document->createElementNS($nameSpace, 'EmailAddresses');

        if (!empty($this->_elements['primaryAddress'])) {
            $element = $document->createElementNS($nameSpace, 'PrimarySmtpAddress', $this->_elements['primaryAddress']);
            $list->appendChild($element);
        }

        foreach ((array)$this->_elements['addresses'] as $address) {
            // skip empty values
            if (empty($address)) {
                continue;
            }

            $element = $document->createElementNS($nameSpace, 'SMTPAddress', $address);
            $list->appendChild($element);
        }

        if ($list->hasChildNodes()) {
            $_domParent->appendChild($list);
        }
    }

}
