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
    
    protected $_properties = [
        'AirSyncBase' => [
            'body'                    => ['type' => 'container', 'class' => 'Syncroton_Model_EmailBody']
        ],
        'Calendar' => [
            'allDayEvent'             => ['type' => 'number'],
            'appointmentReplyTime'    => ['type' => 'datetime'],
            'attendees'               => [
                'type' => 'container',
                'childElement' => 'attendee',
                'class' => 'Syncroton_Model_EventAttendee'
            ],
            'busyStatus'              => ['type' => 'number'],
            'categories'              => ['type' => 'container', 'childElement' => 'category'],
            'deleted'                 => ['type' => 'number'],
            'dtStamp'                 => ['type' => 'datetime'],
            'endTime'                 => ['type' => 'datetime'],
            'exceptionStartTime'      => ['type' => 'datetime'],
            'location'                => ['type' => 'string'],
            'meetingStatus'           => ['type' => 'number'],
            'reminder'                => ['type' => 'number'],
            'responseRequested'       => ['type' => 'boolean'],
            'responseType'            => ['type' => 'number'],
            'sensitivity'             => ['type' => 'number'],
            'startTime'               => ['type' => 'datetime'],
            'subject'                 => ['type' => 'string'],
        ]
    ];    
}
