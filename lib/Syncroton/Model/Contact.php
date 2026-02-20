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
 * class to handle ActiveSync contact
 *
 * @package     Syncroton
 * @subpackage  Model
 * @property    string    $Alias
 * @property    DateTime  $Anniversary
 * @property    string    $AssistantName
 * @property    string    $AssistantPhoneNumber
 * @property    DateTime  $Birthday
 * @property    string    $Business2PhoneNumber
 * @property    string    $BusinessAddressCity
 * @property    Syncroton_Model_EmailBody  $Body
 */

class Syncroton_Model_Contact extends Syncroton_Model_AXMLEntry
{
    protected $_xmlBaseElement = 'ApplicationData';
    
    protected $_properties = [
        'AirSyncBase' => [
            'body'                   => ['type' => 'container', 'class' => 'Syncroton_Model_EmailBody']
        ],
        'Contacts' => [
            'alias'                  => ['type' => 'string', 'supportedSince' => '14.0'],
            'anniversary'            => ['type' => 'datetime'],
            'assistantName'          => ['type' => 'string'],
            'assistantPhoneNumber'   => ['type' => 'string'],
            'birthday'               => ['type' => 'datetime'],
            'business2PhoneNumber'   => ['type' => 'string'],
            'businessAddressCity'    => ['type' => 'string'],
            'businessAddressCountry' => ['type' => 'string'],
            'businessAddressPostalCode' => ['type' => 'string'],
            'businessAddressState'   => ['type' => 'string'],
            'businessAddressStreet'  => ['type' => 'string'],
            'businessFaxNumber'      => ['type' => 'string'],
            'businessPhoneNumber'    => ['type' => 'string'],
            'carPhoneNumber'         => ['type' => 'string'],
            'categories'             => ['type' => 'container', 'childElement' => 'category'],
            'children'               => ['type' => 'container', 'childElement' => 'child'],
            'companyName'            => ['type' => 'string'],
            'department'             => ['type' => 'string'],
            'email1Address'          => ['type' => 'string'],
            'email2Address'          => ['type' => 'string'],
            'email3Address'          => ['type' => 'string'],
            'fileAs'                 => ['type' => 'string'],
            'firstName'              => ['type' => 'string'],
            'home2PhoneNumber'       => ['type' => 'string'],
            'homeAddressCity'        => ['type' => 'string'],
            'homeAddressCountry'     => ['type' => 'string'],
            'homeAddressPostalCode'  => ['type' => 'string'],
            'homeAddressState'       => ['type' => 'string'],
            'homeAddressStreet'      => ['type' => 'string'],
            'homeFaxNumber'          => ['type' => 'string'],
            'homePhoneNumber'        => ['type' => 'string'],
            'jobTitle'               => ['type' => 'string'],
            'lastName'               => ['type' => 'string'],
            'middleName'             => ['type' => 'string'],
            'mobilePhoneNumber'      => ['type' => 'string'],
            'officeLocation'         => ['type' => 'string'],
            'otherAddressCity'       => ['type' => 'string'],
            'otherAddressCountry'    => ['type' => 'string'],
            'otherAddressPostalCode' => ['type' => 'string'],
            'otherAddressState'      => ['type' => 'string'],
            'otherAddressStreet'     => ['type' => 'string'],
            'pagerNumber'            => ['type' => 'string'],
            'picture'                => ['type' => 'string', 'encoding' => 'base64'],
            'padioPhoneNumber'       => ['type' => 'string'],
            'rtf'                    => ['type' => 'string'],
            'spouse'                 => ['type' => 'string'],
            'suffix'                 => ['type' => 'string'],
            'title'                  => ['type' => 'string'],
            'webPage'                => ['type' => 'string'],
            'weightedRank'           => ['type' => 'string', 'supportedSince' => '14.0'],
            'yomiCompanyName'        => ['type' => 'string'],
            'yomiFirstName'          => ['type' => 'string'],
            'yomiLastName'           => ['type' => 'string'],
        ],
        'Contacts2' => [
            'accountName'            => ['type' => 'string'],
            'companyMainPhone'       => ['type' => 'string'],
            'customerId'             => ['type' => 'string'],
            'governmentId'           => ['type' => 'string'],
            'iMAddress'              => ['type' => 'string'],
            'iMAddress2'             => ['type' => 'string'],
            'iMAddress3'             => ['type' => 'string'],
            'managerName'            => ['type' => 'string'],
            'mMS'                    => ['type' => 'string'],
            'nickName'               => ['type' => 'string'],
        ]
    ];
}