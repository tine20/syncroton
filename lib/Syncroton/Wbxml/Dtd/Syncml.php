<?php
/**
 * Syncroton
 *
 * @package     Wbxml
 * @subpackage  Syncml
 * @license     http://www.tine20.org/licenses/lgpl.html LGPL Version 3
 * @copyright   Copyright (c) 2008-2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @author      Lars Kneschke <l.kneschke@metaways.de>
 * @version     $Id:Factory.php 4968 2008-10-17 09:09:33Z l.kneschke@metaways.de $
 */

/**
 * class documentation
 *
 * @package     Wbxml
 * @subpackage  Syncml
 */

class Syncroton_Wbxml_Dtd_Syncml
{
    /**
     * factory function to return a selected contacts backend class
     *
     * @param string $type
     * @return Addressbook_Backend_Interface
     */
    static public function factory ($_type)
    {
        match ($_type) {
            'syncml:syncml1.1', 'syncml:syncml1.2', 'syncml:metinf1.1', 'syncml:metinf1.2', 'syncml:devinf1.1', 'syncml:devinf1.2' => throw new Syncroton_Wbxml_Exception('unsupported DTD: ' . $_type),
            default => $instance,
        };
        return $instance;
    }
}    
