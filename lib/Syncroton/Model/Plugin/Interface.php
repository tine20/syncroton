<?php
/**
 * Syncroton
 *
 * @package     Custom
 * @subpackage  Syncroton
 * @license     http://www.tine20.org/licenses/lgpl.html LGPL Version 3
 * @copyright   Copyright (c) 2009-2014 Metaways Infosystems GmbH (http://www.metaways.de)
 * @copyright   Copyright (c) 2014 Serpro (http://www.serpro.gov.br)
 * @author      Flávio Gomes da Silva Lisboa <flavio.lisboa@serpro.gov.br>
 *
 */

/**
 * interface for Syncroton_Model_Folder plugins
 *
 * @package     Syncroton
 * @subpackage  Model
 */
interface Syncroton_Model_Folder_Plugin_Interface
{
    /**
     *
     * @param array $properties
     */
    public function __construct(array $properties);

    /**
     * @return array
     */
    public function getChangedProperties();
}