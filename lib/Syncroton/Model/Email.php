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
 * @property    array     $attachments
 * @property    string    $contentType
 * @property    array     $flag
 * @property    Syncroton_Model_EmailBody    $body
 * @property    array     $cc
 * @property    array     $to
 * @property    int       $lastVerbExecuted
 * @property    DateTime  $lastVerbExecutionTime
 * @property    int       $read
 */
class Syncroton_Model_Email extends Syncroton_Model_AXMLEntry
{
    const LASTVERB_UNKNOWN       = 0;
    const LASTVERB_REPLYTOSENDER = 1;
    const LASTVERB_REPLYTOALL    = 2;
    const LASTVERB_FORWARD       = 3;
    
    protected $_xmlBaseElement = 'ApplicationData';
    
    protected $_properties = [
        'AirSyncBase' => [
            'attachments'             => ['type' => 'container', 'childElement' => 'attachment', 'class' => 'Syncroton_Model_EmailAttachment'],
            'contentType'             => ['type' => 'string'],
            'body'                    => ['type' => 'container', 'class' => 'Syncroton_Model_EmailBody'],
            'nativeBodyType'          => ['type' => 'number'],
        ],
        'Email' => [
            'busyStatus'              => ['type' => 'number'],
            'categories'              => ['type' => 'container', 'childElement' => 'category', 'supportedSince' => '14.0'],
            'cc'                      => ['type' => 'string'],
            'completeTime'            => ['type' => 'datetime'],
            'contentClass'            => ['type' => 'string'],
            'dateReceived'            => ['type' => 'datetime'],
            'disallowNewTimeProposal' => ['type' => 'number'],
            'displayTo'               => ['type' => 'string'],
            'dTStamp'                 => ['type' => 'datetime'],
            'endTime'                 => ['type' => 'datetime'],
            'flag'                    => ['type' => 'container', 'class' => 'Syncroton_Model_EmailFlag'],
            'from'                    => ['type' => 'string'],
            'globalObjId'             => ['type' => 'string'],
            'importance'              => ['type' => 'number'],
            'instanceType'            => ['type' => 'number'],
            'internetCPID'            => ['type' => 'string'],
            'location'                => ['type' => 'string'],
            'meetingRequest'          => ['type' => 'container', 'class' => 'Syncroton_Model_EmailMeetingRequest'],
            'messageClass'            => ['type' => 'string'],
            'organizer'               => ['type' => 'string'],
            'read'                    => ['type' => 'number'],
            'recurrences'             => ['type' => 'container'],
            'reminder'                => ['type' => 'number'],
            'replyTo'                 => ['type' => 'string'],
            'responseRequested'       => ['type' => 'number'],
            'sensitivity'             => ['type' => 'number'],
            'startTime'               => ['type' => 'datetime'],
            'status'                  => ['type' => 'number'],
            'subject'                 => ['type' => 'string'],
            'threadTopic'             => ['type' => 'string'],
            'timeZone'                => ['type' => 'timezone'],
            'to'                      => ['type' => 'string'],
        ],
        'Email2' => [
            'accountId'             => ['type' => 'string', 'supportedSince' => '14.1'],
            'conversationId'        => ['type' => 'byteArray', 'supportedSince' => '14.0'],
            'conversationIndex'     => ['type' => 'byteArray', 'supportedSince' => '14.0'],
            'lastVerbExecuted'      => ['type' => 'number', 'supportedSince' => '14.0'],
            'lastVerbExecutionTime' => ['type' => 'datetime', 'supportedSince' => '14.0'],
            'meetingMessageType'    => ['type' => 'number', 'supportedSince' => '14.1'],
            'receivedAsBcc'         => ['type' => 'number', 'supportedSince' => '14.0'],
            'sender'                => ['type' => 'string', 'supportedSince' => '14.0'],
            'umCallerID'            => ['type' => 'string', 'supportedSince' => '14.0'],
            'umUserNotes'           => ['type' => 'string', 'supportedSince' => '14.0'],
        ],
    ];
}
