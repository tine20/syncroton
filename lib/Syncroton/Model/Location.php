<?php

/**
 * Syncroton
 *
 * @package     Syncroton
 * @subpackage  Model
 * @license     http://www.tine20.org/licenses/lgpl.html LGPL Version 3
 * @copyright   Copyright (c) 2019 Kolab Systems AG
 * @author      Aleksander Machniak <machniak@kolabsys.com>
 */

/**
 * Class to handle ActiveSync AirSyncBase::Location element
 *
 * @package     Syncroton
 * @subpackage  Model
 *
 * @property float  $accuracy           Accuracy of the values of the Latitude element
 * @property float  $altitude           Altitude of the event's location
 * @property float  $altitudeAccuracy   Accuracy of the value of the Altitude element
 * @property string $annotation         Note about the event's location
 * @property string $city               City of the event's location
 * @property string $country            Country of the event's location
 * @property string $displayName        Display name of the event's location
 * @property float  $latitude           Latitude of the event's location
 * @property string $locationUri        URI for the event's location
 * @property float  $longitude          Longitude of the event's location
 * @property string $postalCode         Postal code for the address of the event's location
 * @property string $state              State or province of the event's location
 * @property string $street             Street address of the event's location
 */
class Syncroton_Model_Location extends Syncroton_Model_AXMLEntry
{
    protected $_xmlBaseElement = 'Location';

    protected $_properties = array(
        'AirSyncBase' => array(
            'accuracy'          => array('type' => 'double', 'supportedSince' => '16.0'),
            'altitude'          => array('type' => 'double', 'supportedSince' => '16.0'),
            'altitudeAccuracy'  => array('type' => 'double', 'supportedSince' => '16.0'),
            'annotation'        => array('type' => 'string', 'supportedSince' => '16.0'),
            'city'              => array('type' => 'string', 'supportedSince' => '16.0'),
            'country'           => array('type' => 'string', 'supportedSince' => '16.0'),
            'displayName'       => array('type' => 'string', 'supportedSince' => '16.0'),
            'latitude'          => array('type' => 'double', 'supportedSince' => '16.0'),
            'locationUri'       => array('type' => 'string', 'supportedSince' => '16.0'),
            'longitude'         => array('type' => 'double', 'supportedSince' => '16.0'),
            'postalCode'        => array('type' => 'string', 'supportedSince' => '16.0'),
            'state'             => array('type' => 'string', 'supportedSince' => '16.0'),
            'street'            => array('type' => 'string', 'supportedSince' => '16.0'),
        )
    );

    /**
     * (non-PHPdoc)
     * @see Syncroton_Model_IEntry::__construct()
     */
    public function __construct($properties = null)
    {
        // Support ActiveSync < 16 location as a string
        if (($properties instanceof SimpleXMLElement && $properties->count() == 0)
            || (is_string($properties) && strlen($properties) > 0)
        ) {
            $properties = (string) $properties;

            // Note: iOS 12.4 does not use LocationUri, URLs are stored in DisplayName
            $this->_elements['displayName'] = $properties;

            $properties = null;
        }

        parent::__construct($properties);
    }

    /**
     * To string converter.
     *
     * It will be used with ActiveSync < 16.0, where Location is a property of type string
     *
     * @return string String representation of the object
     */
    public function __toString()
    {
        if (!empty($this->_elements['displayName'])) {
            return (string) $this->_elements['displayName'];
        }

        if (!empty($this->_elements['locationUri'])) {
            return (string) $this->_elements['locationUri'];
        }

        return '';
    }
}
