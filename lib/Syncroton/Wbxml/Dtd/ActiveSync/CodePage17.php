<?php

/**
 * Syncroton
 *
 * @package     Wbxml
 * @subpackage  ActiveSync
 * @license     http://www.tine20.org/licenses/lgpl.html LGPL Version 3
 * @copyright   Copyright (c) 2008-2012 Metaways Infosystems GmbH (http://www.metaways.de)
 * @author      Lars Kneschke <l.kneschke@metaways.de>
 */

/**
 * class documentation
 *
 * @package     Wbxml
 * @subpackage  ActiveSync
 */
class Syncroton_Wbxml_Dtd_ActiveSync_CodePage17 extends Syncroton_Wbxml_Dtd_ActiveSync_Abstract
{
    protected $_codePageNumber = 17;
    protected $_codePageName   = 'AirSyncBase';
    protected $_tags = array(
        'BodyPreference'    => 0x05,
        'Type'              => 0x06,
        'TruncationSize'    => 0x07,
        'AllOrNone'         => 0x08,
        'Body'              => 0x0a,
        'Data'              => 0x0b,
        'EstimatedDataSize' => 0x0c,
        'Truncated'         => 0x0d,
        'Attachments'       => 0x0e,
        'Attachment'        => 0x0f,
        'DisplayName'       => 0x10,
        'FileReference'     => 0x11,
        'Method'            => 0x12,
        'ContentId'         => 0x13,
        'ContentLocation'   => 0x14,
        'IsInline'          => 0x15,
        'NativeBodyType'    => 0x16,
        'ContentType'       => 0x17,
        'Preview'           => 0x18,
        'BodyPartReference' => 0x19,
        'BodyPart'          => 0x1a,
        'Status'            => 0x1b,
        'Add'               => 0x1c,
        'Delete'            => 0x1d,
        'ClientId'          => 0x1e,
        'Content'           => 0x1f,
        'Location'          => 0x20,
        'Annotation'        => 0x21,
        'Street'            => 0x22,
        'City'              => 0x23,
        'State'             => 0x24,
        'Country'           => 0x25,
        'PostalCode'        => 0x26,
        'Latitude'          => 0x27,
        'Longitude'         => 0x28,
        'Accuracy'          => 0x29,
        'Altitude'          => 0x2a,
        'AltitudeAccuracy'  => 0x2b,
        'LocationUri'       => 0x2c,
        'InstanceId'        => 0x2d,
    );
}
