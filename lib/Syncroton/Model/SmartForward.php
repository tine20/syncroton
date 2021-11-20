<?php
/**
 * Syncroton
 *
 * @package     Syncroton
 * @subpackage  Model
 * @license     http://www.tine20.org/licenses/lgpl.html LGPL Version 3
 * @copyright   Copyright (c) 2012-2012 Metaways Infosystems GmbH (http://www.metaways.de)
 * @copyright   Copyright (c) 2012-2012 KolabSYstems AG (http://www.kolabsys.com)
 * @author      Lars Kneschke <l.kneschke@metaways.de>
 * @author      Aleksander Machniak <machniak@kolabsys.com>
 */

/**
 * Class to handle ActiveSync SmartForward element
 *
 * @package    Syncroton
 * @subpackage Model
 *
 * @property  string                      $accountId
 * @property  string                      $clientId
 * @property  Syncroton_Model_Forwardee[] $forwardees
 * @property  string                      $mime
 * @property  bool                        $replaceMime
 * @property  bool                        $saveInSentItems
 * @property  string                      $source
 * @property  int                         $status
 * @property  string                      $templateID
 */
class Syncroton_Model_SmartForward extends Syncroton_Model_AXMLEntry
{
    protected $_properties = array(
        'ComposeMail' => array(
            'accountId'       => array('type' => 'string'),
            'clientId'        => array('type' => 'string'),
            'forwardees'      => array('type' => 'container', 'childElement' => 'forwardee', 'supportedSince' => '16.0'),
            'mime'            => array('type' => 'byteArray'),
            'replaceMime'     => array('type' => 'string'),
            'saveInSentItems' => array('type' => 'string'),
            'source'          => array('type' => 'container'), // or string
            'status'          => array('type' => 'number'),
        ),
        'RightsManagement' => array(
            'templateID'      => array('type' => 'string'),
        )
    );
}
