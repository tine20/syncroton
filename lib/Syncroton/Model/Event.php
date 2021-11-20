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
 * @property Syncroton_Model_Attachments     $attachments
 * @property Syncroton_Model_EventAttendee[] $attendees
 * @property int                             $busyStatus
 * @property array                           $categories
 * @property string                          $clientUid
 * @property bool                            $disallowNewTimeProposal
 * @property DateTime                        $dtStamp
 * @property DateTime                        $endTime
 * @property Syncroton_Model_EventException[] $exceptions
 * @property Syncroton_Model_Location        $location
 * @property int                             $meetingStatus
 * @property string                          $onlineMeetingConfLink
 * @property string                          $onlineMeetingExternalLink
 * @property string                          $organizerEmail
 * @property string                          $organizerName
 * @property Syncroton_Model_EventRecurrence $recurrence
 * @property int                             $reminder
 * @property int                             $responseRequested
 * @property int                             $responseType
 * @property int                             $sensitivity
 * @property DateTime                        $startTime
 * @property string                          $subject
 * @property string                          $timezone
 * @property string                          $uID
 */
class Syncroton_Model_Event extends Syncroton_Model_AXMLEntry
{
    /**
     * busy status constants
     */
    const BUSY_STATUS_FREE      = 0;
    const BUSY_STATUS_TENATTIVE = 1;
    const BUSY_STATUS_BUSY      = 2;

    protected $_dateTimeFormat = "Ymd\THis\Z";
    protected $_xmlBaseElement = 'ApplicationData';
    protected $_properties = array(
        'AirSyncBase' => array(
            'attachments'  => array('type' => 'container', 'class' => 'Syncroton_Model_Attachments', 'supportedSince' => '16.0'),
            'body'         => array('type' => 'container', 'class' => 'Syncroton_Model_EmailBody'),
            'location'     => array('type' => 'container', 'class' => 'Syncroton_Model_Location', 'supportedSince' => '16.0'),
        ),
        'Calendar' => array(
            'allDayEvent'               => array('type' => 'number'),
            'appointmentReplyTime'      => array('type' => 'datetime'),
            'attendees'                 => array('type' => 'container', 'childElement' => 'attendee', 'class' => 'Syncroton_Model_EventAttendee'),
            'busyStatus'                => array('type' => 'number'),
            'categories'                => array('type' => 'container', 'childElement' => 'category'),
            'clientUid'                 => array('type' => 'string', 'supportedSince' => '16.0'),
            'disallowNewTimeProposal'   => array('type' => 'number'),
            'dtStamp'                   => array('type' => 'datetime'),
            'endTime'                   => array('type' => 'datetime'),
            'exceptions'                => array('type' => 'container', 'childElement' => 'exception', 'class' => 'Syncroton_Model_EventException'),
            'location'                  => array('type' => 'string', 'supportedUntil' => '16.0'),
            'meetingStatus'             => array('type' => 'number'),
            'onlineMeetingConfLink'     => array('type' => 'string'),
            'onlineMeetingExternalLink' => array('type' => 'string'),
            'organizerEmail'            => array('type' => 'string'),
            'organizerName'             => array('type' => 'string'),
            'recurrence'                => array('type' => 'container', 'class' => 'Syncroton_Model_EventRecurrence'),
            'reminder'                  => array('type' => 'number'),
            'responseRequested'         => array('type' => 'number'),
            'responseType'              => array('type' => 'number'),
            'sensitivity'               => array('type' => 'number'),
            'startTime'                 => array('type' => 'datetime'),
            'subject'                   => array('type' => 'string'),
            'timezone'                  => array('type' => 'timezone'),
            'uID'                       => array('type' => 'string'),
        )
    );

    /**
     * (non-PHPdoc)
     * @see Syncroton_Model_IEntry::appendXML()
     * @todo handle Attendees element
     */
    public function appendXML(DOMElement $domParrent, Syncroton_Model_IDevice $device)
    {
        parent::appendXML($domParrent, $device);

        $exceptionElements = $domParrent->getElementsByTagName('Exception');
        $parentFields      = array('AllDayEvent'/*, 'Attendees'*/, 'Body', 'BusyStatus'/*, 'Categories'*/, 'DtStamp', 'EndTime', 'Location', 'MeetingStatus', 'Reminder', 'ResponseType', 'Sensitivity', 'StartTime', 'Subject');

        if ($exceptionElements->length > 0) {
            $mainEventElement = $exceptionElements->item(0)->parentNode->parentNode;

            foreach ($mainEventElement->childNodes as $childNode) {
                if (in_array($childNode->localName, $parentFields)) {
                    foreach ($exceptionElements as $exception) {
                        $elementsToLeftOut = $exception->getElementsByTagName($childNode->localName);

                        foreach ($elementsToLeftOut as $elementToLeftOut) {
                            if ($elementToLeftOut->nodeValue == $childNode->nodeValue) {
                                $exception->removeChild($elementToLeftOut);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * some elements of an exception can be left out, if they have the same value
     * like the main event
     *
     * this function copies these elements to the exception for backends which need
     * this elements in the exceptions too. Tine 2.0 needs this for example.
     */
    public function copyFieldsFromParent()
    {
        if (isset($this->_elements['exceptions']) && is_array($this->_elements['exceptions'])) {
            foreach ($this->_elements['exceptions'] as $exception) {
                // no need to update deleted exceptions
                if ($exception->deleted == 1) {
                    continue;
                }

                $parentFields = array('allDayEvent', 'attendees', 'body', 'busyStatus', 'categories', 'dtStamp', 'endTime', 'location', 'meetingStatus', 'reminder', 'responseType', 'sensitivity', 'startTime', 'subject');

                foreach ($parentFields as $field) {
                    if (!isset($exception->$field) && isset($this->_elements[$field])) {
                        $exception->$field = $this->_elements[$field];
                    }
                }
            }
        }
    }
}
