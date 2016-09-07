<?php
/**
 * Syncroton
 *
 * @package     Syncroton
 * @subpackage  Backend
 * @license     http://www.tine20.org/licenses/lgpl.html LGPL Version 3
 * @copyright   Copyright (c) 2009-2012 Metaways Infosystems GmbH (http://www.metaways.de)
 * @copyright   Copyright (c) 2012-2014 Kolab Systems AG (http://www.kolabsys.com)
 * @author      Lars Kneschke <l.kneschke@metaways.de>
 * @author      Aleksander Machniak <machniak@kolabsys.com>
 */

/**
 * Interface class for device backend
 *
 * @package     Syncroton
 * @subpackage  Backend
 */
interface Syncroton_Backend_IDevice extends Syncroton_Backend_IBackend
{
    /**
     * @param unknown_type $userId
     * @param unknown_type $deviceId
     * @return Syncroton_Model_IDevice
     */
    public function getUserDevice($userId, $deviceId);

    /**
     * Returns list of user accounts
     *
     * @param Syncroton_Model_Device $device The device
     *
     * @return array List of Syncroton_Model_Account objects
     */
    public function userAccounts($device);

    /**
     * Returns OOF information
     *
     * @param array $request Oof/Get request data
     *
     * @return Syncroton_Model_Oof Response object or NULL if OOF is not supported
     * @throws Syncroton_Exception_Status
     */
    public function getOOF($request);

    /**
     * Sets OOF information
     *
     * @param Syncroton_Model_Oof $request Request object
     *
     * @throws Syncroton_Exception_Status
     */
    public function setOOF($request);
}
