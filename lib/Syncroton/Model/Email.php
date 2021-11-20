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
 * class to handle ActiveSync email
 *
 * @package     Syncroton
 * @subpackage  Model
 *
 * @property    string          $accountId
 * @property    Syncroton_Model_Attachments $attachments
 * @property    string          $bcc
 * @property    Syncroton_Model_EmailBody $body
 * @property    int             $busyStatus
 * @property    array           $categories
 * @property    string          $cc
 * @property    DateTime        $completeTime
 * @property    string          $contentClass
 * @property    string          $contentType
 * @property    string          $conversationId
 * @property    string          $conversationIndex
 * @property    DateTime        $dateReceived
 * @property    bool            $disallowNewTimeProposal
 * @property    string          $displayTo
 * @property    DateTime        $dtStamp
 * @property    DateTime        $endTime
 * @property    Syncroton_Model_EmailFlag $flag
 * @property    string          $from
 * @property    string          $globalObjId
 * @property    int             $instanceType
 * @property    string          $internetCPID
 * @property    int             $importance
 * @property    bool            $isDraft
 * @property    int             $lastVerbExecuted
 * @property    DateTime        $lastVerbExecutionTime
 * @property    Syncroton_Model_Location $location
 * @property    int             $meetingMessageType
 * @property    Syncroton_Model_MeetingRequest $meetingRequest
 * @property    string          $messageClass
 * @property    int             $nativeBodyType
 * @property    string          $organizer
 * @property    int             $read
 * @property    int             $receivedAsBcc
 * @property    Syncroton_Model_EventRecurrence[] $recurrences
 * @property    int             $reminder
 * @property    string          $replyTo
 * @property    int             $responseRequested
 * @property    string          $sender
 * @property    int             $sensitivity
 * @property    DateTime        $startTime
 * @property    int             $status
 * @property    string          $subject
 * @property    string          $threadTopic
 * @property    string          $timeZone
 * @property    string          $to
 * @property    string          $umCallerID
 * @property    string          $umUserNotes
 */
class Syncroton_Model_Email extends Syncroton_Model_AXMLEntry
{
    const LASTVERB_UNKNOWN       = 0;
    const LASTVERB_REPLYTOSENDER = 1;
    const LASTVERB_REPLYTOALL    = 2;
    const LASTVERB_FORWARD       = 3;

    protected $_xmlBaseElement = 'ApplicationData';

    protected $_properties = array(
        'AirSyncBase' => array(
            'attachments'             => array('type' => 'container', 'class' => 'Syncroton_Model_Attachments'),
            'contentType'             => array('type' => 'string'),
            'body'                    => array('type' => 'container', 'class' => 'Syncroton_Model_EmailBody'),
            'location'                => array('type' => 'container', 'class' => 'Syncroton_Model_Location', 'supportedSince' => '16.0'),
            'nativeBodyType'          => array('type' => 'number'),
        ),
        'Email' => array(
            'busyStatus'              => array('type' => 'number'),
            'categories'              => array('type' => 'container', 'childElement' => 'category', 'supportedSince' => '14.0'),
            'cc'                      => array('type' => 'string'),
            'completeTime'            => array('type' => 'datetime'),
            'contentClass'            => array('type' => 'string'),
            'dateReceived'            => array('type' => 'datetime'),
            'disallowNewTimeProposal' => array('type' => 'number'),
            'displayTo'               => array('type' => 'string'),
            'dTStamp'                 => array('type' => 'datetime'),
            'endTime'                 => array('type' => 'datetime'),
            'flag'                    => array('type' => 'container', 'class' => 'Syncroton_Model_EmailFlag'),
            'from'                    => array('type' => 'string'),
            'globalObjId'             => array('type' => 'string'),
            'importance'              => array('type' => 'number'),
            'instanceType'            => array('type' => 'number'),
            'internetCPID'            => array('type' => 'string'),
            'location'                => array('type' => 'string', 'supportedUntil' => '16.0'),
            'meetingRequest'          => array('type' => 'container', 'class' => 'Syncroton_Model_MeetingRequest'),
            'messageClass'            => array('type' => 'string'),
            'organizer'               => array('type' => 'string'),
            'read'                    => array('type' => 'number'),
            'recurrences'             => array('type' => 'container'),
            'reminder'                => array('type' => 'number'),
            'replyTo'                 => array('type' => 'string'),
            'responseRequested'       => array('type' => 'number'),
            'sensitivity'             => array('type' => 'number'),
            'startTime'               => array('type' => 'datetime'),
            'status'                  => array('type' => 'number'),
            'subject'                 => array('type' => 'string'),
            'threadTopic'             => array('type' => 'string'),
            'timeZone'                => array('type' => 'timezone'),
            'to'                      => array('type' => 'string'),
        ),
        'Email2' => array(
            'accountId'             => array('type' => 'string', 'supportedSince' => '14.1'),
            'bcc'                   => array('type' => 'string', 'supportedSince' => '16.0'),
            'conversationId'        => array('type' => 'byteArray', 'supportedSince' => '14.0'),
            'conversationIndex'     => array('type' => 'byteArray', 'supportedSince' => '14.0'),
            'isDraft'               => array('type' => 'number', 'supportedSince' => '16.0'),
            'lastVerbExecuted'      => array('type' => 'number', 'supportedSince' => '14.0'),
            'lastVerbExecutionTime' => array('type' => 'datetime', 'supportedSince' => '14.0'),
            'meetingMessageType'    => array('type' => 'number', 'supportedSince' => '14.1'),
            'receivedAsBcc'         => array('type' => 'number', 'supportedSince' => '14.0'),
            'sender'                => array('type' => 'string', 'supportedSince' => '14.0'),
            'umCallerID'            => array('type' => 'string', 'supportedSince' => '14.0'),
            'umUserNotes'           => array('type' => 'string', 'supportedSince' => '14.0'),
        ),
    );
}
