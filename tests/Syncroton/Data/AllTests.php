<?php
/**
 * Syncroton
 *
 * @package     Syncroton
 * @subpackage  Tests
 * @license     http://www.tine20.org/licenses/lgpl.html LGPL Version 3
 * @copyright   Copyright (c) 2009-2022 Metaways Infosystems GmbH (http://www.metaways.de)
 * @author      Lars Kneschke <l.kneschke@metaways.de>
 */

/**
 * class to test <...>
 *
 * @package     Syncroton
 * @subpackage  Tests
 */
class Syncroton_Data_AllTests
{
    public static function suite ()
    {
        $suite = new \PHPUnit\Framework\TestSuite('Syncroton all data backend tests');
        
        $suite->addTestSuite('Syncroton_Data_ContactsTests');
        
        return $suite;
    }
}
