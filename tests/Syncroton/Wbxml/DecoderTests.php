<?php
/**
 * Syncroton
 *
 * @package     Syncroton
 * @subpackage  Tests
 * @license     http://www.tine20.org/licenses/lgpl.html LGPL Version 3
 * @copyright   Copyright (c) 2012-2022 Metaways Infosystems GmbH (http://www.metaways.de)
 * @author      Lars Kneschke <l.kneschke@metaways.de>
 */

/**
 * Test class for Syncroton_Wbxml_Decoder
 * 
 * @package     Syncroton
 * @subpackage  Tests
 */
class Syncroton_Wbxml_DecoderTests extends \PHPUnit\Framework\TestCase
{
    /**
     * 
     */
    public function testDecode()
    {
        $decoder = new Syncroton_Wbxml_Decoder(fopen(__DIR__ . '/files/simple.wbxml', 'r+'));
        $requestBody = $decoder->decode();
        
        $this->assertTrue($requestBody instanceof DomDocument);
    }
}
