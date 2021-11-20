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
 * abstract class to handle ActiveSync entry
 *
 * @package     Syncroton
 * @subpackage  Model
 */
abstract class Syncroton_Model_AXMLEntry extends Syncroton_Model_AEntry implements Syncroton_Model_IXMLEntry
{
    protected $_xmlBaseElement;

    protected $_properties = array();

    protected $_dateTimeFormat = "Y-m-d\TH:i:s.000\Z";

    /**
     * (non-PHPdoc)
     * @see Syncroton_Model_IEntry::__construct()
     */
    public function __construct($properties = null)
    {
        if ($properties instanceof SimpleXMLElement) {
            $this->setFromSimpleXMLElement($properties);
        } elseif (is_array($properties)) {
            $this->setFromArray($properties);
        }

        $this->_isDirty = false;
    }

    /**
     * (non-PHPdoc)
     * @see Syncroton_Model_IEntry::appendXML()
     */
    public function appendXML(DOMElement $domParent, Syncroton_Model_IDevice $device)
    {
        $this->_addXMLNamespaces($domParent);

        foreach ($this->_elements as $elementName => $value) {
            // skip empty values
            if ($value === null || $value === '' || (is_array($value) && empty($value))) {
                continue;
            }

            list ($nameSpace, $elementProperties) = $this->_getElementProperties($elementName, $device->acsversion);

            if ($nameSpace == 'Internal') {
                continue;
            }

            $nameSpace = 'uri:' . $nameSpace;

            if (isset($elementProperties['childElement'])) {
                $element = $domParent->ownerDocument->createElementNS($nameSpace, ucfirst($elementName));
                foreach($value as $subValue) {
                    $subElement = $domParent->ownerDocument->createElementNS($nameSpace, ucfirst($elementProperties['childElement']));
                    $this->_appendXMLElement($device, $subElement, $elementProperties, $subValue);
                    $element->appendChild($subElement);
                }
                $domParent->appendChild($element);
            } else if ($elementProperties['type'] == 'container' && !empty($elementProperties['multiple'])) {
                foreach ($value as $element) {
                    $container = $domParent->ownerDocument->createElementNS($nameSpace, ucfirst($elementName));
                    $element->appendXML($container, $device);
                    $domParent->appendChild($container);
                }
            } else if ($elementProperties['type'] == 'none') {
                if ($value) {
                    $element = $domParent->ownerDocument->createElementNS($nameSpace, ucfirst($elementName));
                    $domParent->appendChild($element);
                }
            } else {
                $element = $domParent->ownerDocument->createElementNS($nameSpace, ucfirst($elementName));
                $this->_appendXMLElement($device, $element, $elementProperties, $value);
                $domParent->appendChild($element);
            }
        }
    }

    /**
     * (non-PHPdoc)
     * @see Syncroton_Model_IEntry::getProperties()
     */
    public function getProperties($selectedNamespace = null)
    {
        $properties = array();

        foreach ($this->_properties as $namespace => $namespaceProperties) {
            if ($selectedNamespace !== null && $namespace != $selectedNamespace) {
                continue;
            }
            $properties = array_merge($properties, array_keys($namespaceProperties));
        }

        return array_unique($properties);
    }

    /**
     * set properties from SimpleXMLElement object
     *
     * @param SimpleXMLElement $xmlCollection
     * @throws InvalidArgumentException
     */
    public function setFromSimpleXMLElement(SimpleXMLElement $properties)
    {
        if (!in_array($properties->getName(), (array) $this->_xmlBaseElement)) {
            throw new InvalidArgumentException('Unexpected element name: ' . $properties->getName());
        }

        foreach (array_keys($this->_properties) as $namespace) {
            if ($namespace == 'Internal') {
                continue;
            }

            $this->_parseNamespace($namespace, $properties);
        }
    }

    /**
     * add needed xml namespaces to DomDocument
     *
     * @param DOMElement $domParent
     */
    protected function _addXMLNamespaces(DOMElement $domParent)
    {
        foreach (array_keys($this->_properties) as $namespace) {
            // don't add default namespace again
            if ($domParent->ownerDocument->documentElement->namespaceURI != 'uri:'.$namespace) {
                $domParent->ownerDocument->documentElement->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:'.$namespace, 'uri:'.$namespace);
            }
        }
    }

    protected function _appendXMLElement(Syncroton_Model_IDevice $device, DOMElement $element, $elementProperties, $value)
    {
        if ($value instanceof Syncroton_Model_IEntry && $elementProperties['type'] === 'container') {
            $value->appendXML($element, $device);
        } else {
            if ($value instanceof DateTime) {
                $value = $value->format($this->_dateTimeFormat);
            } elseif (isset($elementProperties['encoding']) && $elementProperties['encoding'] == 'base64') {
                if (is_resource($value)) {
                    rewind($value);
                    $value = stream_get_contents($value);
                }
                $value = base64_encode($value);
            }

            if ($elementProperties['type'] == 'byteArray') {
                $element->setAttributeNS('uri:Syncroton', 'Syncroton:encoding', 'opaque');
                // encode to base64; the wbxml encoder will base64_decode it again
                // this way we can also transport data, which would break the xmlparser otherwise
                $element->appendChild($element->ownerDocument->createCDATASection(base64_encode($value)));
            } else if ($elementProperties['type'] == 'double') {
                $element->appendChild($element->ownerDocument->createTextNode((string) floatval($value)));
            } else {
                $value = (string) $value;
                // strip off any non printable control characters
                if (!ctype_print($value)) {
                    $value = $this->_removeControlChars($value);
                }

                $element->appendChild($element->ownerDocument->createTextNode($this->_enforceUTF8($value)));
            }
        }
    }

    /**
     * remove control chars from a string which are not allowed in XML values
     *
     * @param string $dirty An input string
     * @return string Cleaned up string
     */
    protected function _removeControlChars($dirty)
    {
        // Replace non-character UTF-8 sequences that cause XML Parser to fail
        // https://git.kolab.org/T1311
        $dirty = str_replace(array("\xEF\xBF\xBE", "\xEF\xBF\xBF"), '', $dirty);

        // Replace ASCII control-characters
        return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $dirty);
    }

    /**
     * enforce >valid< utf-8 encoding
     *
     * @param  string  $dirty  the string with maybe invalid utf-8 data
     * @return string  string with valid utf-8
     */
    protected function _enforceUTF8($dirty)
    {
        if (function_exists('iconv')) {
            if (($clean = @iconv('UTF-8', 'UTF-8//IGNORE', $dirty)) !== false) {
                return $clean;
            }
        }

        if (function_exists('mb_convert_encoding')) {
            if (($clean = mb_convert_encoding($dirty, 'UTF-8', 'UTF-8')) !== false) {
                return $clean;
            }
        }

        return $dirty;
    }

    /**
     *
     * @param unknown_type $element Element name
     * @param string       $version Protocol version
     * @throws InvalidArgumentException
     * @return array
     */
    protected function _getElementProperties($element, $version = null)
    {

        foreach ($this->_properties as $namespace => $namespaceProperties) {
            if (array_key_exists($element, $namespaceProperties)) {
                $elementProperties = $namespaceProperties[$element];

                if ($version) {
                    $supportedSince = isset($elementProperties['supportedSince']) ? $elementProperties['supportedSince'] : '12.0';
                    $supportedUntil = isset($elementProperties['supportedUntil']) ? $elementProperties['supportedUntil'] : '9999';

                    if (version_compare($version, $supportedSince, '<') || version_compare($version, $supportedUntil, '>')) {
                        continue;
                    }
                }

                return array($namespace, $elementProperties);
            }
        }

        throw new InvalidArgumentException("$element is no valid property of " . get_class($this));
    }

    protected function _parseNamespace($nameSpace, SimpleXMLElement $properties)
    {
        // fetch data from the specified namespace
        $children = $properties->children("uri:$nameSpace");

        foreach ($children as $elementName => $xmlElement) {
            $elementName = lcfirst($elementName);

            if (!isset($this->_properties[$nameSpace][$elementName])) {
                continue;
            }

            list (, $elementProperties) = $this->_getElementProperties($elementName);

            switch ($elementProperties['type']) {
                case 'container':
                    if (!empty($elementProperties['multiple'])) {
                        $property = (array) $this->$elementName;

                        if (isset($elementProperties['class'])) {
                            $property[] = new $elementProperties['class']($xmlElement);
                        } else {
                            $property[] = (string) $xmlElement;
                        }
                    } else if (isset($elementProperties['childElement'])) {
                        $property = array();
                        $childElement = ucfirst($elementProperties['childElement']);

                        foreach ($xmlElement->$childElement as $subXmlElement) {
                            if (isset($elementProperties['class'])) {
                                $property[] = new $elementProperties['class']($subXmlElement);
                            } else {
                                $property[] = (string) $subXmlElement;
                            }
                        }
                    } else {
                        $subClassName = isset($elementProperties['class']) ? $elementProperties['class'] : get_class($this) . ucfirst($elementName);

                        $property = new $subClassName($xmlElement);
                    }

                    break;

                case 'datetime':
                    $property = new DateTime((string) $xmlElement, new DateTimeZone('UTC'));
                    break;

                case 'number':
                    $property = (int) $xmlElement;
                    break;

                case 'double':
                    $property = (float) $xmlElement;
                    break;

                default:
                    $property = (string) $xmlElement;
                    break;
            }

            if (isset($elementProperties['encoding']) && $elementProperties['encoding'] == 'base64') {
                $property = base64_decode($property);
            }

            $this->$elementName = $property;
        }
    }

    public function &__get($name)
    {
        $this->_getElementProperties($name);

        return $this->_elements[$name];
    }

    public function __set($name, $value)
    {
        list ($nameSpace, $properties) = $this->_getElementProperties($name);

        if ($properties['type'] == 'datetime' && !$value instanceof DateTime) {
            throw new InvalidArgumentException("value for $name must be an instance of DateTime");
        }

        if (!array_key_exists($name, $this->_elements) || $this->_elements[$name] != $value) {
            // Always use Location object
            if ($name === 'location' && !($value instanceof Syncroton_Model_Location)) {
                $value = new Syncroton_Model_Location($value);
            }
            // Always use Attachments
            else if ($name === 'attachments' && !($value instanceof Syncroton_Model_Attachments)) {
                $value = new Syncroton_Model_Attachments($value);
            }

            $this->_elements[$name] = $value;

            $this->_isDirty = true;
        }
    }
}
