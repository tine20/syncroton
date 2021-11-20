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
 * class to handle ActiveSync event
 *
 * @package     Syncroton
 * @subpackage  Model
 *
 * @property  int       $attendeeStatus
 * @property  int       $attendeeType
 * @property  string    $email
 * @property  string    $name
 * @property  datetime  $proposedEndTime
 * @property  datetime  $proposedStartTime
 */
class Syncroton_Model_EventAttendee extends Syncroton_Model_AXMLEntry
{
    /**
     * attendee status
     */
    const ATTENDEE_STATUS_UNKNOWN       = 0;
    const ATTENDEE_STATUS_TENTATIVE     = 2;
    const ATTENDEE_STATUS_ACCEPTED      = 3;
    const ATTENDEE_STATUS_DECLINED      = 4;
    const ATTENDEE_STATUS_NOTRESPONDED  = 5;

    /**
     * attendee types
     */
    const ATTENDEE_TYPE_REQUIRED = 1;
    const ATTENDEE_TYPE_OPTIONAL = 2;
    const ATTENDEE_TYPE_RESOURCE = 3;

    protected $_xmlBaseElement = 'Attendee';

    protected $_properties = array(
        'Calendar' => array(
            'attendeeStatus'          => array('type' => 'number'),
            'attendeeType'            => array('type' => 'number'),
            'email'                   => array('type' => 'string'),
            'name'                    => array('type' => 'string'),
        ),
        'MeetingResponse' => array(
            'proposedEndTime'         => array('type' => 'datetime', 'supportedSince' => '16.1'),
            'proposedStartTime'       => array('type' => 'datetime', 'supportedSince' => '16.1'),
        ),
    );
}
