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
 * class to handle ActiveSync task
 *
 * @package     Syncroton
 * @subpackage  Model
 * @property    string  $class
 * @property    string  $collectionId
 * @property    bool    $deletesAsMoves
 * @property    bool    $getChanges
 * @property    string  $syncKey
 * @property    int     $windowSize
 */
class Syncroton_Model_Task extends Syncroton_Model_AXMLEntry
{
    protected $_xmlBaseElement = 'ApplicationData';
    
    protected $_properties = [
        'AirSyncBase' => [
            'body'                   => ['type' => 'container', 'class' => 'Syncroton_Model_EmailBody']
        ],
        'Tasks' => [
            'categories'              => ['type' => 'container', 'childElement' => 'category'],
            'complete'                => ['type' => 'number'],
            'dateCompleted'           => ['type' => 'datetime'],
            'dueDate'                 => ['type' => 'datetime'],
            'importance'              => ['type' => 'number'],
            'recurrence'              => ['type' => 'container'],
            'reminderSet'             => ['type' => 'number'],
            'reminderTime'            => ['type' => 'datetime'],
            'sensitivity'             => ['type' => 'number'],
            'startDate'               => ['type' => 'datetime'],
            'subject'                 => ['type' => 'string'],
            'utcDueDate'              => ['type' => 'datetime'],
            'utcStartDate'            => ['type' => 'datetime'],
        ]
    ];
}