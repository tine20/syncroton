<?php
/**
 * Syncroton
 *
 * @package     Syncroton
 * @subpackage  Command
 * @license     http://www.tine20.org/licenses/lgpl.html LGPL Version 3
 * @copyright   Copyright (c) 2009-2012 Metaways Infosystems GmbH (http://www.metaways.de)
 * @author      Lars Kneschke <l.kneschke@metaways.de>
 */

/**
 * class to handle ActiveSync SmartForward command
 *
 * @package     Syncroton
 * @subpackage  Command
 */
class Syncroton_Command_SmartForward extends Syncroton_Command_SendMail
{
    protected $_defaultNameSpace    = 'uri:ComposeMail';
    protected $_documentElement     = 'SmartForward';

    /**
     * Execute email sending method of data controller
     */
    protected function sendMail($dataController)
    {
        $dataController->forwardEmail($this->_source, $this->_mime, $this->_saveInSent, $this->_replaceMime);
    }
}
