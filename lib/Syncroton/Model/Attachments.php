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
 * Class to handle ActiveSync Attachments element
 *
 * @package     Syncroton
 * @subpackage  Model
 */
class Syncroton_Model_Attachments extends Syncroton_Model_AXMLEntry
{
    const TYPE_ATTACHMENT = 1;
    const TYPE_ADD        = 2;
    const TYPE_DELETE     = 3;

    protected $_xmlBaseElement   = 'Attachments';
    protected $_xmlChildElements = array('Attachment', 'Add', 'Delete');
    protected $_nameSpace        = 'AirSyncBase';

    /**
     * (non-PHPdoc)
     * @see Syncroton_Model_IEntry::setFromArray()
     */
    public function setFromArray(array $properties)
    {
        foreach ($properties as $attachment) {
            $this->_elements[] = new Syncroton_Model_Attachment($attachment);
        }
    }

    /**
     * Set properties from SimpleXMLElement object
     *
     * @param SimpleXMLElement $element
     * @throws InvalidArgumentException
     */
    public function setFromSimpleXMLElement(SimpleXMLElement $element)
    {
        if (!in_array($element->getName(), (array) $this->_xmlBaseElement)) {
            throw new InvalidArgumentException('Unexpected element name: ' . $element->getName());
        }

        $children = $element->children("uri:" . $this->_nameSpace);

        foreach ($children as $elementName => $xmlElement) {
            $elementName = lcfirst($elementName);
            if (in_array($elementName, $this->_xmlChildElements)) {
                $attachment = new Syncroton_Model_Attachment($xmlElement);
                $attachment->setElementType($elementName);

                $this->_elements[] = $attachment;
            }
        }
    }

    /**
     * (non-PHPdoc)
     * @see Syncroton_Model_IEntry::appendXML()
     */
    public function appendXML(DOMElement $domParent, Syncroton_Model_IDevice $device)
    {
        $nameSpace  = 'uri:' . $this->_nameSpace;
        $properties = array('type' => 'container');

        foreach ($this->_elements as $attachment) {
            $elementName = $attachment->getElementType();
            $element     = $domParent->ownerDocument->createElementNS($nameSpace, $elementName);
            $attachment->appendXML($element, $device);
            $domParent->appendChild($element);
        }
    }
}
