<?php
/**
 * Syncroton
 *
 * @package     Syncroton
 * @subpackage  Exception
 * @license     http://www.tine20.org/licenses/lgpl.html LGPL Version 3
 * @copyright   Copyright (c) 2012-2012 Metaways Infosystems GmbH (http://www.metaways.de)
 * @copyright   Copyright (c) 2012-2014 Kolab Systems AG (http://www.kolabsys.com)
 * @author      Lars Kneschke <l.kneschke@metaways.de>
 * @author      Aleksander Machniak <machniak@kolabsys.com>
 */

/**
 * Exception for Status element in Settings response
 *
 * @package     Syncroton
 * @subpackage  Exception
 */
class Syncroton_Exception_Status_Settings extends Syncroton_Exception_Status
{
    const PROTOCOL_ERROR         = 2;
    const ACCESS_DENIED          = 3;
    const SERVICE_UNAVAILABLE    = 4;
    const INVALID_ARGUMENTS      = 5;
    const CONFLICTING_ARGUMENTS  = 6;
    const DENIED_BY_POLICY       = 7;

    /**
     * Error messages assigned to error codes
     *
     * @var array
     */
    protected $_errorMessages = array(
        self::PROTOCOL_ERROR        => "Protocol error",
        self::ACCESS_DENIED         => "Access denied",
        self::SERVICE_UNAVAILABLE   => "Server unavailable",
        self::INVALID_ARGUMENTS     => "Invalid arguments",
        self::CONFLICTING_ARGUMENTS => "Conflicting arguments",
        self::DENIED_BY_POLICY      => "Denied by policy. Disabled by administrator",
    );
}
