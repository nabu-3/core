<?php
/* ===========================================================================
 * File generated automatically by Nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2017/03/24 12:55:37 UTC
 * ===========================================================================
 * Copyright 2009-2011 Rafael Gutierrez Martinez
 * Copyright 2012-2013 Welma WEB MKT LABS, S.L.
 * Copyright 2014-2017 Where Ideas Simply Come True, S.L.
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *     http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace nabu\data\site\base;

use \nabu\core\CNabuEngine;
use \nabu\core\exceptions\ENabuCoreException;
use \nabu\data\site\traits\TNabuSiteTargetChild;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity Site Target Medioteca stored in the storage named nb_site_target_medioteca.
 * @author Rafael Gutiérrez Martínez <rgutierrez@wiscot.com>
 * @version 3.0.12 Surface
 * @package \nabu\data\site\base
 */
abstract class CNabuSiteTargetMedioteca extends CNabuDBInternalObject
{
    use TNabuSiteTargetChild;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_site_target An instance of CNabuSiteTargetMedioteca or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_site_target_id, or a valid ID.
     * @param mixed $nb_medioteca An instance of CNabuSiteTargetMedioteca or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_medioteca_id, or a valid ID.
     */
    public function __construct($nb_site_target = false, $nb_medioteca = false)
    {
        if ($nb_site_target) {
            $this->transferMixedValue($nb_site_target, 'nb_site_target_id');
        }
        
        if ($nb_medioteca) {
            $this->transferMixedValue($nb_medioteca, 'nb_medioteca_id');
        }
        
        parent::__construct();
    }

    /**
     * Get the file name and path where is stored the descriptor in JSON format.
     * @return string Return the file name with the full path
     */
    public static function getStorageDescriptorPath()
    {
        return preg_replace('/.php$/', '.json', __FILE__);
    }

    /**
     * Get the table name represented by this class
     * @return string Return the table name
     */
    public static function getStorageName()
    {
        return 'nb_site_target_medioteca';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_site_target_id') && $this->isValueNumeric('nb_medioteca_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_site_target_medioteca '
                   . "where nb_site_target_id=%nb_site_target_id\$d "
                     . "and nb_medioteca_id=%nb_medioteca_id\$d "
              )
            : null;
    }

    /**
     * Gets a filtered list of Site Target Medioteca instances represented as an array. Params allows the capability of
     * select a subset of fields, order by concrete fields, or truncate the list by a number of rows starting in an
     * offset.
     * @throws \nabu\core\exceptions\ENabuCoreException Raises an exception if $fields or $order have invalid values.
     * @param string $q Query string to filter results using a context index.
     * @param string|array $fields List of fields to put in the results.
     * @param string|array $order List of fields to order the results. Each field can be suffixed with "ASC" or "DESC"
     * to determine the short order
     * @param int $offset Offset of first row in the results having the first row at offset 0.
     * @param int $num_items Number of continue rows to get as maximum in the results.
     * @return array Returns an array with all rows found using the criteria.
     */
    public static function getFilteredSiteTargetMediotecaList($q = null, $fields = null, $order = null, $offset = 0, $num_items = 0)
    {
        $fields_part = nb_prefixFieldList(CNabuSiteTargetMedioteca::getStorageName(), $fields, false, true, '`');
        $order_part = nb_prefixFieldList(CNabuSiteTargetMedioteca::getStorageName(), $fields, false, false, '`');
        
        if ($num_items !== 0) {
            $limit_part = ($offset > 0 ? $offset . ', ' : '') . $num_items;
        } else {
            $limit_part = false;
        }
        
        $nb_item_list = CNabuEngine::getEngine()->getMainDB()->getQueryAsArray(
            "select " . ($fields_part ? $fields_part . ' ' : '* ')
            . 'from nb_site_target_medioteca '
            . ($order_part ? "order by $order_part " : '')
            . ($limit_part ? "limit $limit_part" : ''),
            array(
            )
        );
        
        return $nb_item_list;
    }

    /**
     * Get Site Target Id attribute value
     * @return int Returns the Site Target Id value
     */
    public function getSiteTargetId()
    {
        return $this->getValue('nb_site_target_id');
    }

    /**
     * Sets the Site Target Id attribute value
     * @param int $nb_site_target_id New value for attribute
     * @return CNabuSiteTargetMedioteca Returns $this
     */
    public function setSiteTargetId($nb_site_target_id)
    {
        if ($nb_site_target_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_site_target_id")
            );
        }
        $this->setValue('nb_site_target_id', $nb_site_target_id);
        
        return $this;
    }

    /**
     * Get Medioteca Id attribute value
     * @return int Returns the Medioteca Id value
     */
    public function getMediotecaId()
    {
        return $this->getValue('nb_medioteca_id');
    }

    /**
     * Sets the Medioteca Id attribute value
     * @param int $nb_medioteca_id New value for attribute
     * @return CNabuSiteTargetMedioteca Returns $this
     */
    public function setMediotecaId($nb_medioteca_id)
    {
        if ($nb_medioteca_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_medioteca_id")
            );
        }
        $this->setValue('nb_medioteca_id', $nb_medioteca_id);
        
        return $this;
    }

    /**
     * Get Site Target Medioteca Alias attribute value
     * @return null|string Returns the Site Target Medioteca Alias value
     */
    public function getAlias()
    {
        return $this->getValue('nb_site_target_medioteca_alias');
    }

    /**
     * Sets the Site Target Medioteca Alias attribute value
     * @param null|string $alias New value for attribute
     * @return CNabuSiteTargetMedioteca Returns $this
     */
    public function setAlias($alias)
    {
        $this->setValue('nb_site_target_medioteca_alias', $alias);
        
        return $this;
    }
}
