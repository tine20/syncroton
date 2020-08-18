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
 * Class to handle MeetingResponse::SendResponse
 *
 * @package     Syncroton
 * @subpackage  Model
 *
 * @property  Syncroton_Model_EmailBody  $body
 * @property  datetime  $proposedEndTime
 * @property  datetime  $proposedStartTime
 */
class Syncroton_Model_SendResponse extends Syncroton_Model_AXMLEntry
{
    protected $_xmlBaseElement = 'SendResponse';

    protected $_properties = array(
        'AirSyncBase' => array(
            'body'               => array('type' => 'container', 'class' => 'Syncroton_Model_EmailBody')
        ),
        'MeetingResponse' => array(
            'proposedEndTime'    => array('type' => 'datetime', 'supportedSince' => '16.1'),
            'proposedStartTime'  => array('type' => 'datetime', 'supportedSince' => '16.1'),
        ),
    );
}
