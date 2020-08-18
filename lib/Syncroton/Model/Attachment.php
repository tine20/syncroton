<?php

/**
 * Syncroton
 *
 * @package     Syncroton
 * @subpackage  Model
 * @license     http://www.tine20.org/licenses/lgpl.html LGPL Version 3
 * @copyright   Copyright (c) 2012-2012 Metaways Infosystems GmbH (http://www.metaways.de)
 * @author      Lars Kneschke <l.kneschke@metaways.de>
 */

/**
 * Class to handle ActiveSync Attachment (and Attachments::Delete/Add)
 *
 * @package     Syncroton
 * @subpackage  Model
 * @property    string  $clientId
 * @property    string  $content
 * @property    string  $contentId
 * @property    string  $contentLocation
 * @property    string  $contentType
 * @property    string  $displayName
 * @property    int     $estimatedDataSize
 * @property    string  $fileReference
 * @property    bool    $isInline
 * @property    int     $method
 * @property    int     $umAttDuration
 * @property    int     $umAttOrder
 */
class Syncroton_Model_Attachment extends Syncroton_Model_AXMLEntry
{
    const METHOD_NORMAL = 1;
    const METHOD_EML    = 5;
    const METHOD_OLE    = 6;

    protected $_xmlBaseElement = array('Attachment', 'Add', 'Delete');

    protected $_properties = array(
        'AirSyncBase' => array(
            'clientId'                => array('type' => 'string', 'supportedSince' => '16.0'),
            'content'                 => array('type' => 'byteArray', 'supportedSince' => '16.0'), // Attachments->Add
            'contentId'               => array('type' => 'string'),
            'contentLocation'         => array('type' => 'string'),
            'contentType'             => array('type' => 'string', 'supportedSince' => '16.0'), // Attachments->Add
            'displayName'             => array('type' => 'string'),
            'estimatedDataSize'       => array('type' => 'number'),
            'fileReference'           => array('type' => 'string'),
            'isInline'                => array('type' => 'number'),
            'method'                  => array('type' => 'number'),
        ),
        'Email2' => array(
            'umAttDuration'         => array('type' => 'number', 'supportedSince' => '14.0'),
            'umAttOrder'            => array('type' => 'number', 'supportedSince' => '14.0'),
        ),
    );

    protected $_elementType = 'Attachment';


    /**
     * Sets object type
     *
     * @param string $type Object type (one of: Attachment, Add, Delete)
     */
    public function setElementType(string $type)
    {
        if (in_array($type, $this->xmlChildElements)) {
            $this->_elementType = $type;
        }
    }

    /**
     * Returns object type
     *
     * @return string Object type (one of: Attachment, Add, Delete)
     */
    public function getElementType()
    {
        return $this->_elementType;
    }
}
