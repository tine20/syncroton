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
 * @property    int       $calendarType
 * @property    int       $dayOfMonth
 * @property    int       $dayOfWeek
 * @property    int       $firstDayOfWeek
 * @property    int       $interval
 * @property    int       $isLeapMonth
 * @property    int       $monthOfYear
 * @property    int       $occurrences
 * @property    int       $type
 * @property    DateTime  $until
 * @property    int       $weekOfMonth
 */
class Syncroton_Model_EventRecurrence extends Syncroton_Model_AXMLEntry
{
    /**
     * recur types
     */
    const TYPE_DAILY          = 0;     // Recurs daily.
    const TYPE_WEEKLY         = 1;     // Recurs weekly
    const TYPE_MONTHLY        = 2;     // Recurs monthly
    const TYPE_MONTHLY_DAYN   = 3;     // Recurs monthly on the nth day
    const TYPE_YEARLY         = 5;     // Recurs yearly
    const TYPE_YEARLY_DAYN    = 6;     // Recurs yearly on the nth day

    /**
     * day of week constants
     */
    const RECUR_DOW_SUNDAY      = 1;
    const RECUR_DOW_MONDAY      = 2;
    const RECUR_DOW_TUESDAY     = 4;
    const RECUR_DOW_WEDNESDAY   = 8;
    const RECUR_DOW_THURSDAY    = 16;
    const RECUR_DOW_FRIDAY      = 32;
    const RECUR_DOW_SATURDAY    = 64;

    protected $_xmlBaseElement = 'Recurrence';

    protected $_dateTimeFormat = "Ymd\THis\Z";

    protected $_properties = array(
        'Calendar' => array(
            'calendarType'            => array('type' => 'number'),
            'dayOfMonth'              => array('type' => 'number'),
            'dayOfWeek'               => array('type' => 'number'),
            'firstDayOfWeek'          => array('type' => 'number'),
            'interval'                => array('type' => 'number'),
            'isLeapMonth'             => array('type' => 'number'),
            'monthOfYear'             => array('type' => 'number'),
            'occurrences'             => array('type' => 'number'),
            'type'                    => array('type' => 'number'),
            'until'                   => array('type' => 'datetime'),
            'weekOfMonth'             => array('type' => 'number'),
        )
    );
}
