<?php

/**
 * Syncroton
 *
 * @package     Wbxml
 * @subpackage  ActiveSync
 * @license     http://www.tine20.org/licenses/lgpl.html LGPL Version 3
 * @copyright   Copyright (c) 2012-2012 Metaways Infosystems GmbH (http://www.metaways.de)
 * @author      Lars Kneschke <l.kneschke@metaways.de>
 */

/**
 * class documentation
 *
 * @package     Wbxml
 * @subpackage  ActiveSync
 */
class Syncroton_Wbxml_Dtd_ActiveSync_CodePage25 extends Syncroton_Wbxml_Dtd_ActiveSync_Abstract
{
    protected $_codePageNumber = 25;
    protected $_codePageName   = 'Find';
    protected $_tags = array(
        'Find'                      => 0x05,
        'SearchId'                  => 0x06,
        'ExecuteSearch'             => 0x07,
        'MailBoxSearchCriterion'    => 0x08,
        'Query'                     => 0x09,
        'Status'                    => 0x0a,
        'FreeText'                  => 0x0b,
        'Options'                   => 0x0c,
        'Range'                     => 0x0d,
        'DeepTraversal'             => 0x0e,
        'Response'                  => 0x11,
        'Result'                    => 0x12,
        'Properties'                => 0x13,
        'Preview'                   => 0x14,
        'HasAttachments'            => 0x15,
        'Total'                     => 0x16,
        'DisplayCc'                 => 0x17,
        'DisplayBcc'                => 0x18,
        'GalSearchCriterion'        => 0x19,
        'MaxPictures'               => 0x20,
        'MaxSize'                   => 0x21,
        'Picture'                   => 0x22,
    );
}
