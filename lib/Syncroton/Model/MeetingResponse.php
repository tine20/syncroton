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
 * class to handle MeetingResponse request
 *
 * @package     Syncroton
 * @subpackage  Model
 *
 * @property  string  $calendarId
 * @property  string  $collectionId
 * @property  string  $instanceId
 * @property  string  $longId
 * @property  string  $requestId
 * @property  Syncroton_Model_SendResponse $sendResponse
 * @property  int     $userResponse
 */
class Syncroton_Model_MeetingResponse extends Syncroton_Model_AXMLEntry
{
    /**
     * attendee status
     */
    const RESPONSE_ACCEPTED  = 1;
    const RESPONSE_TENTATIVE = 2;
    const RESPONSE_DECLINED  = 3;

    protected $_xmlBaseElement = 'Request';

    protected $_properties = array(
        'MeetingResponse' => array(
            'calendarId'    => array('type' => 'string'),
            'collectionId'  => array('type' => 'string'),
            'instanceId'    => array('type' => 'datetime'),
            'requestId'     => array('type' => 'string'),
            'sendResponse'  => array('type' => 'container', 'class' => 'Syncroton_Model_SendResponse', 'supportedSince' => '16.0'),
            'userResponse'  => array('type' => 'number'),
        ),
        'Search' => array(
            'longId'        => array('type' => 'string')
        )
    );
}
