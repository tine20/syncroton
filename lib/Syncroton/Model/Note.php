<?php
/**
 * Syncroton
 *
 * @package     Syncroton
 * @subpackage  Model
 * @license     http://www.tine20.org/licenses/lgpl.html LGPL Version 3
 * @copyright   Copyright (c) 2014 Kolab Systems AG (http://www.kolabsys.com)
 * @author      Aleksander Machniak <machniak@kolabsys.com>
 */

/**
 * class to handle ActiveSync note
 *
 * @package     Syncroton
 * @subpackage  Model
 * @property    Syncroton_Model_EmailBody body
 * @property    array                     categories
 * @property    DateTime                  lastModifiedDate
 * @property    string                    messageClass
 * @property    string                    subject
 */
class Syncroton_Model_Note extends Syncroton_Model_AXMLEntry
{
    protected $_xmlBaseElement = 'ApplicationData';

    protected $_properties = array(
        'AirSyncBase' => array(
            'body'             => array('type' => 'container', 'class' => 'Syncroton_Model_EmailBody')
        ),
        'Notes' => array(
            'categories'       => array('type' => 'container', 'childElement' => 'category'),
            'lastModifiedDate' => array('type' => 'datetime'),
            'messageClass'     => array('type' => 'string'),
            'subject'          => array('type' => 'string'),
        )
    );
}