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
 * Class to handle ComposeMail::Forwardee
 *
 * @package     Syncroton
 * @subpackage  Model
 *
 * @property    string  $email  E-mail address
 * @property    string  $name   Display name
 */
class Syncroton_Model_Forwardee extends Syncroton_Model_AXMLEntry
{
    protected $_xmlBaseElement = 'Forwardee';

    protected $_properties = array(
        'ComposeMail' => array(
            'email'     => array('type' => 'string'),
            'name'      => array('type' => 'string'),
        ),
    );
}
