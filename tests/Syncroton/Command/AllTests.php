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
class Syncroton_Command_AllTests
{
    public static function main ()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }
    
    public static function suite ()
    {
        $suite = new PHPUnit\Framework\TestSuite('Syncroton all ActiveSync command tests');
        
        $suite->addTestSuite(Syncroton_Command_FolderCreateTests::class);
        $suite->addTestSuite(Syncroton_Command_FolderDeleteTests::class);
        $suite->addTestSuite(Syncroton_Command_FolderSyncTests::class);
        $suite->addTestSuite(Syncroton_Command_FolderUpdateTests::class);
        $suite->addTestSuite(Syncroton_Command_GetAttachmentTests::class);
        $suite->addTestSuite(Syncroton_Command_GetItemEstimateTests::class);
        $suite->addTestSuite(Syncroton_Command_ItemOperationsTests::class);
        $suite->addTestSuite(Syncroton_Command_MeetingResponseTests::class);
        $suite->addTestSuite(Syncroton_Command_MoveItemsTests::class);
        $suite->addTestSuite(Syncroton_Command_PingTests::class);
        $suite->addTestSuite(Syncroton_Command_ProvisionTests::class);
        $suite->addTestSuite(Syncroton_Command_SearchTests::class);
        $suite->addTestSuite(Syncroton_Command_SettingsTests::class);
        $suite->addTestSuite(Syncroton_Command_SmartForwardTests::class);
        $suite->addTestSuite(Syncroton_Command_SendMailTests::class);
        $suite->addTestSuite(Syncroton_Command_SyncTests::class);
        
        return $suite;
    }
}
