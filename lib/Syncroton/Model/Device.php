<?php
/**
 * Syncroton
 *
 * @package     Syncroton
 * @subpackage  Model
 * @license     http://www.tine20.org/licenses/lgpl.html LGPL Version 3
 * @copyright   Copyright (c) 2009-2012 Metaways Infosystems GmbH (http://www.metaways.de)
 * @author      Lars Kneschke <l.kneschke@metaways.de>
 */

/**
 * class to handle ActiveSync Sync command
 *
 * @package     Syncroton
 * @subpackage  Model
 */
class Syncroton_Model_Device extends Syncroton_Model_AEntry implements Syncroton_Model_IDevice
{
    const TYPE_IPHONE          = 'iphone';
    const TYPE_WEBOS           = 'webos';
    const TYPE_ANDROID         = 'android';
    const TYPE_ANDROID_40      = 'android40';
    const TYPE_SMASUNGGALAXYS2 = 'samsunggti9100'; // Samsung Galaxy S-3
    const TYPE_BLACKBERRY      = 'blackberry';
    
    /**
     * Returns major firmware version of this device
     * 
     * @return int/string
     */
    public function getMajorVersion()
    {
        switch (strtolower($this->devicetype)) {
            case Syncroton_Model_Device::TYPE_BLACKBERRY:
                if (preg_match('/(.+)\/(.+)/', $this->useragent, $matches)) {
                    list(, $name, $version) = $matches;
                    return $version;
                }
                break;
                
            case Syncroton_Model_Device::TYPE_IPHONE:
                if (preg_match('/(.+)\/(\d+)\.(\d+)/', $this->useragent, $matches)) {
                    list(, $name, $majorVersion, $minorVersion) = $matches;
                    return $majorVersion;
                }
                break;

            case Syncroton_Model_Device::TYPE_ANDROID:
                if (preg_match('/Android\/(\d+)\.(\d+)/', $this->useragent, $matches)) {
                    list(, $majorVersion, $minorVersion) = $matches;
                    return $majorVersion;
                } else if (! empty($this->os) && preg_match('/Android (\d+)\.(\d+)/', $this->os, $matches)) {
                    list(, $majorVersion, $minorVersion) = $matches;
                    return $majorVersion;
                }
                break;
        }
        
        return 0;
    }
}

