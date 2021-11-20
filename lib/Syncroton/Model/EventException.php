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
 * Class to handle ActiveSync event
 *
 * @package     Syncroton
 * @subpackage  Model
 *
 * @property Syncroton_Model_EmailBody       $body
 * @property bool                            $allDayEvent
 * @property DateTime                        $appointmentReplyTime
 * @property Syncroton_Model_EventAttendee[] $attendees
 * @property int                             $busyStatus
 * @property array                           $categories
 * @property bool                            $deleted
 * @property DateTime                        $dtStamp
 * @property DateTime                        $endTime
 * @property DateTime                        exceptionStartTime
 * @property Syncroton_Model_Location        $location
 * @property int                             $meetingStatus
 * @property int                             $reminder
 * @property int                             $responseType
 * @property int                             $sensitivity
 * @property DateTime                        $startTime
 * @property string                          $subject
 */
class Syncroton_Model_EventException extends Syncroton_Model_AXMLEntry
{
    protected $_xmlBaseElement = 'Exception';
    protected $_dateTimeFormat = "Ymd\THis\Z";
    protected $_properties = array(
        'AirSyncBase' => array(
            'body'                    => array('type' => 'container', 'class' => 'Syncroton_Model_EmailBody'),
            'location'                => array('type' => 'container', 'class' => 'Syncroton_Model_Location', 'supportedSince' => '16.0'),
        ),
        'Calendar' => array(
            'allDayEvent'             => array('type' => 'number'),
            'appointmentReplyTime'    => array('type' => 'datetime'),
            'attendees'               => array('type' => 'container', 'childElement' => 'attendee', 'class' => 'Syncroton_Model_EventAttendee'),
            'busyStatus'              => array('type' => 'number'),
            'categories'              => array('type' => 'container', 'childElement' => 'category'),
            'deleted'                 => array('type' => 'number'),
            'dtStamp'                 => array('type' => 'datetime'),
            'endTime'                 => array('type' => 'datetime'),
            'exceptionStartTime'      => array('type' => 'datetime'),
            'location'                => array('type' => 'string', 'supportedUntil' => '16.0'),
            'meetingStatus'           => array('type' => 'number'),
            'reminder'                => array('type' => 'number'),
            'responseType'            => array('type' => 'number'),
            'sensitivity'             => array('type' => 'number'),
            'startTime'               => array('type' => 'datetime'),
            'subject'                 => array('type' => 'string'),
        )
    );
}
