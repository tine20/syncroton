<?php
/**
 * Syncroton
 *
 * @package     Syncroton
 * @subpackage  Model
 * @license     http://www.tine20.org/licenses/lgpl.html LGPL Version 3
 * @copyright   Copyright (c) 2012-2019 Metaways Infosystems GmbH (http://www.metaways.de)
 * @author      Lars Kneschke <l.kneschke@metaways.de>
 */

/**
 * class to handle ActiveSync event
 *
 * @package     Syncroton
 * @subpackage  Model
 * @property    string      $class
 * @property    string      $collectionId
 * @property    bool        $deletesAsMoves
 * @property    bool        $getChanges
 * @property    string      $syncKey
 * @property    int         $windowSize
 * @property    int         $meetingStatus
 * @property    int         $responseType
 * @property    bool        $responseRequested
 * @property    DateTime    $appointmentReplyTime
 */

class Syncroton_Model_EventException extends Syncroton_Model_AXMLEntry
{    
    protected $_xmlBaseElement = 'Exception';
    
    protected $_dateTimeFormat = "Ymd\THis\Z";
    
    protected $_properties = array(
        'AirSyncBase' => array(
            'body'                    => array('type' => 'container', 'class' => 'Syncroton_Model_EmailBody')
        ),
        'Calendar' => array(
            'allDayEvent'             => array('type' => 'number'),
            'appointmentReplyTime'    => array('type' => 'datetime'),
            'attendees'               => array(
                'type' => 'container',
                'childElement' => 'attendee',
                'class' => 'Syncroton_Model_EventAttendee'
            ),
            'busyStatus'              => array('type' => 'number'),
            'categories'              => array('type' => 'container', 'childElement' => 'category'),
            'deleted'                 => array('type' => 'number'),
            'dtStamp'                 => array('type' => 'datetime'),
            'endTime'                 => array('type' => 'datetime'),
            'exceptionStartTime'      => array('type' => 'datetime'),
            'location'                => array('type' => 'string'),
            'meetingStatus'           => array('type' => 'number'),
            'reminder'                => array('type' => 'number'),
            'responseRequested'       => array('type' => 'boolean'),
            'responseType'            => array('type' => 'number'),
            'sensitivity'             => array('type' => 'number'),
            'startTime'               => array('type' => 'datetime'),
            'subject'                 => array('type' => 'string'),
        )
    );    
}
