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
 * class to handle ActiveSync Policy element
 *
 * @package     Syncroton
 * @subpackage  Model
 */
class Syncroton_Model_Policy extends Syncroton_Model_AXMLEntry implements Syncroton_Model_IPolicy
{
    protected $_xmlBaseElement = 'EASProvisionDoc';

    protected $_properties = [
        'Internal' => [
            'id'                                   => ['type' => 'string'],
            'description'                          => ['type' => 'string'],
            'name'                                 => ['type' => 'string'],
            'policyKey'                            => ['type' => 'string'],
        ],
        'Provision' => [
            'allowBluetooth'                       => ['type' => 'number'],
            'allowBrowser'                         => ['type' => 'number'],
            'allowCamera'                          => ['type' => 'number'],
            'allowConsumerEmail'                   => ['type' => 'number'],
            'allowDesktopSync'                     => ['type' => 'number'],
            'allowHTMLEmail'                       => ['type' => 'number'],
            'allowInternetSharing'                 => ['type' => 'number'],
            'allowIrDA'                            => ['type' => 'number'],
            'allowPOPIMAPEmail'                    => ['type' => 'number'],
            'allowRemoteDesktop'                   => ['type' => 'number'],
            'allowSimpleDevicePassword'            => ['type' => 'number'],
            'allowSMIMEEncryptionAlgorithmNegotiation' => ['type' => 'number'],
            'allowSMIMESoftCerts'                  => ['type' => 'number'],
            'allowStorageCard'                     => ['type' => 'number'],
            'allowTextMessaging'                   => ['type' => 'number'],
            'allowUnsignedApplications'            => ['type' => 'number'],
            'allowUnsignedInstallationPackages'    => ['type' => 'number'],
            'allowWifi'                            => ['type' => 'number'],
            'alphanumericDevicePasswordRequired'   => ['type' => 'number'],
            'approvedApplicationList'              => ['type' => 'container', 'childName' => 'Hash'],
            'attachmentsEnabled'                   => ['type' => 'number'],
            'devicePasswordEnabled'                => ['type' => 'number'],
            'devicePasswordExpiration'             => ['type' => 'number'],
            'devicePasswordHistory'                => ['type' => 'number'],
            'maxAttachmentSize'                    => ['type' => 'number'],
            'maxCalendarAgeFilter'                 => ['type' => 'number'],
            'maxDevicePasswordFailedAttempts'      => ['type' => 'number'],
            'maxEmailAgeFilter'                    => ['type' => 'number'],
            'maxEmailBodyTruncationSize'           => ['type' => 'number'],
            'maxEmailHTMLBodyTruncationSize'       => ['type' => 'number'],
            'maxInactivityTimeDeviceLock'          => ['type' => 'number'],
            'minDevicePasswordComplexCharacters'   => ['type' => 'number'],
            'minDevicePasswordLength'              => ['type' => 'number'],
            'passwordRecoveryEnabled'              => ['type' => 'number'],
            'requireDeviceEncryption'              => ['type' => 'number'],
            'requireEncryptedSMIMEMessages'        => ['type' => 'number'],
            'requireEncryptionSMIMEAlgorithm'      => ['type' => 'number'],
            'requireManualSyncWhenRoaming'         => ['type' => 'number'],
            'requireSignedSMIMEAlgorithm'          => ['type' => 'number'],
            'requireSignedSMIMEMessages'           => ['type' => 'number'],
            'requireStorageCardEncryption'         => ['type' => 'number'],
            'unapprovedInROMApplicationList'       => ['type' => 'container', 'childName' => 'ApplicationName']
        ]
    ];
}
