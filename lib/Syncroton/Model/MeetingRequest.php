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
 * class to handle Email:MeetingRequest
 *
 * @package     Syncroton
 * @subpackage  Model
 * @property    bool            $allDayEvent
 * @property    int             $busyStatus
 * @property    int             $disallowNewTimeProposal
 * @property    DateTime        $dtStamp
 * @property    DateTime        $endTime
 * @property    Syncroton_Model_Forwardee[] $forwardees
 * @property    string          $globalObjId
 * @property    int             $instanceType
 * @property    Syncroton_Model_Location    $location
 * @property    int             $meetingMessageType
 * @property    string          $organizer
 * @property    datetime        $proposedEndTime
 * @property    datetime        $proposedStartTime
 * @property    string          $recurrenceId
 * @property    array           $recurrences
 * @property    int             $reminder
 * @property    int             $responseRequested
 * @property    int             $sensitivity
 * @property    DateTime        $startTime
 * @property    string          $timezone
 */
class Syncroton_Model_MeetingRequest extends Syncroton_Model_AXMLEntry
{
    /**
     * busy status constants
     */
    const BUSY_STATUS_FREE      = 0;
    const BUSY_STATUS_TENATTIVE = 1;
    const BUSY_STATUS_BUSY      = 2;
    const BUSY_STATUS_OUT       = 3;

    /**
     * sensitivity constants
     */
    const SENSITIVITY_NORMAL       = 0;
    const SENSITIVITY_PERSONAL     = 1;
    const SENSITIVITY_PRIVATE      = 2;
    const SENSITIVITY_CONFIDENTIAL = 3;

    /**
     * instanceType constants
     */
    const TYPE_NORMAL              = 0;
    const TYPE_RECURRING_MASTER    = 1;
    const TYPE_RECURRING_SINGLE    = 2;
    const TYPE_RECURRING_EXCEPTION = 3;

    /**
     * messageType constants
     */
    const MESSAGE_TYPE_NORMAL      = 0;
    const MESSAGE_TYPE_REQUEST     = 1;
    const MESSAGE_TYPE_FULL_UPDATE = 2;
    const MESSAGE_TYPE_INFO_UPDATE = 3;
    const MESSAGE_TYPE_OUTDATED    = 4;
    const MESSAGE_TYPE_COPY        = 5;
    const MESSAGE_TYPE_DELEGATED   = 6;

    protected $_dateTimeFormat = "Ymd\THis\Z";

    protected $_xmlBaseElement = 'MeetingRequest';

    protected $_properties = array(
        'AirSyncBase' => array(
            'location'                  => array('type' => 'container', 'class' => 'Syncroton_Model_Location', 'supportedSince' => '16.0'),
        ),
        'ComposeMail' => array(
            'forwardees'                => array('type' => 'container', 'childElement' => 'forwardee', 'supportedSince' => '16.0'),
        ),
        'Email' => array(
            'allDayEvent'               => array('type' => 'number'),
            'busyStatus'                => array('type' => 'number'),
            'disallowNewTimeProposal'   => array('type' => 'number'),
            'dtStamp'                   => array('type' => 'datetime'),
            'endTime'                   => array('type' => 'datetime'),
            'globalObjId'               => array('type' => 'string'),
            'instanceType'              => array('type' => 'datetime'),
            'location'                  => array('type' => 'string', 'supportedUntil' => '16.0'),
            'organizer'                 => array('type' => 'string'), //e-mail address
            'recurrenceId'              => array('type' => 'datetime'),
            'recurrences'               => array('type' => 'container'),
            'reminder'                  => array('type' => 'number'),
            'responseRequested'         => array('type' => 'number'),
            'sensitivity'               => array('type' => 'number'),
            'startTime'                 => array('type' => 'datetime'),
            'timeZone'                  => array('type' => 'timezone'),
        ),
        'Email2' => array(
            'meetingMessageType'        => array('type' => 'number'),
        ),
        'MeetingResponse' => array(
            'proposedEndTime'           => array('type' => 'datetime', 'supportedSince' => '16.1'),
            'proposedStartTime'         => array('type' => 'datetime', 'supportedSince' => '16.1'),
        ),
    );
}
