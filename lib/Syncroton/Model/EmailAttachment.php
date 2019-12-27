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
 * Class to handle ActiveSync Attachment
 *
 * @package     Syncroton
 * @subpackage  Model
 * @property    string  $clientId
 * @property    string  $contentId
 * @property    string  $contentLocation
 * @property    string  $displayName
 * @property    int     $estimatedDataSize
 * @property    string  $fileReference
 * @property    bool    $isInline
 * @property    int     $method
 * @property    int     $umAttDuration
 * @property    int     $umAttOrder
 */
class Syncroton_Model_EmailAttachment extends Syncroton_Model_Attachment
{
    protected $_xmlBaseElement = 'Attachment';
}
