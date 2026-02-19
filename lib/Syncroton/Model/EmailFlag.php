<?php
/**
 * Syncroton
 *
 * @package     Syncroton
 * @subpackage  Model
 * @license     http://www.tine20.org/licenses/lgpl.html LGPL Version 3
 * @copyright   Copyright (c) 2012-2012 Metaways Infosystems GmbH (http://www.metaways.de)
 * @copyright   Copyright (c) 2012-2012 Kolab Systems AG (http://www.kolabsys.com)
 * @author      Lars Kneschke <l.kneschke@metaways.de>
 * @author      Aleksander Machniak <machniak@kolabsys.com>
 */

/**
 * class to handle ActiveSync Flag element
 *
 * @package     Syncroton
 * @subpackage  Model
 * @property    DateTime  $CompleteTime
 * @property    DateTime  $DateCompleted
 * @property    DateTime  $DueDate
 * @property    string    $FlagType
 * @property    DateTime  $OrdinalDate
 * @property    int       $ReminderSet
 * @property    DateTime  $ReminderTime
 * @property    DateTime  $StartDate
 * @property    string    $Status
 * @property    string    $Subject
 * @property    string    $SubOrdinalDate
 * @property    DateTime  $UtcDueDate
 * @property    DateTime  $UtcStartDate
 */
class Syncroton_Model_EmailFlag extends Syncroton_Model_AXMLEntry
{
    const STATUS_CLEARED  = 0;
    const STATUS_COMPLETE = 1;
    const STATUS_ACTIVE   = 2;

    protected $_xmlBaseElement = 'Flag';

    protected $_properties = [
        'Email' => [
            'completeTime'       => ['type' => 'datetime'],
            'flagType'           => ['type' => 'string'],
            'status'             => ['type' => 'number'],
        ],
        'Tasks' => [
            'dateCompleted'      => ['type' => 'datetime'],
            'dueDate'            => ['type' => 'datetime'],
            'ordinalDate'        => ['type' => 'datetime'],
            'reminderSet'        => ['type' => 'number'],
            'reminderTime'       => ['type' => 'datetime'],
            'startDate'          => ['type' => 'datetime'],
            'subject'            => ['type' => 'string'],
            'subOrdinalDate'     => ['type' => 'string'],
            'utcStartDate'       => ['type' => 'datetime'],
            'utcDueDate'         => ['type' => 'datetime'],
        ],
    ];
}
