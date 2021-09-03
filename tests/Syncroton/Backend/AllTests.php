<?php
/**
 * Syncroton
 *
 * @package     Syncroton
 * @subpackage  Tests
 * @license     http://www.tine20.org/licenses/lgpl.html LGPL Version 3
 * @copyright   Copyright (c) 2009-2012 Metaways Infosystems GmbH (http://www.metaways.de)
 * @author      Lars Kneschke <l.kneschke@metaways.de>
 */

/**
 * class to test <...>
 *
 * @package     Syncroton
 * @subpackage  Tests
 */
class Syncroton_Backend_AllTests
{
    public static function main ()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }
    
    public static function suite ()
    {
        $suite = new PHPUnit\Framework\TestSuite('Syncroton All Backend Tests');
        
        $suite->addTestSuite(Syncroton_Backend_ContentTests::class);
        $suite->addTestSuite(Syncroton_Backend_DeviceTests::class);
        $suite->addTestSuite(Syncroton_Backend_FolderTests::class);
        $suite->addTestSuite(Syncroton_Backend_PolicyTests::class);
        $suite->addTestSuite(Syncroton_Backend_SyncStateTests::class);
        
        return $suite;
    }
}
