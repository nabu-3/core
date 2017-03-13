<?php
/* ===========================================================================
 * File generated automatically by Nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2017/03/13 17:24:17 UTC
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
use \nabu\data\CNabuDataObject;
use \nabu\data\lang\interfaces\INabuTranslated;
use \nabu\data\lang\interfaces\INabuTranslation;
use \nabu\data\lang\traits\TNabuTranslated;
use \nabu\data\site\builtin\CNabuBuiltInSiteMapLanguage;
use \nabu\data\site\CNabuSite;
use \nabu\data\site\CNabuSiteMap;
use \nabu\data\site\CNabuSiteMapLanguage;
use \nabu\data\site\CNabuSiteMapList;
use \nabu\data\site\traits\TNabuSiteChild;
use \nabu\data\site\traits\TNabuSiteTargetChild;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity Site Map stored in the storage named nb_site_map.
 * @author Rafael Gutiérrez Martínez <rgutierrez@wiscot.com>
 * @version 3.0.12 Surface
 * @package \nabu\data\site\base
 */
abstract class CNabuSiteMapBase extends CNabuDBInternalObject implements INabuTranslated
{
    use TNabuSiteChild;
    use TNabuSiteTargetChild;
    use TNabuTranslated;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_site_map An instance of CNabuSiteMapBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_site_map_id, or a valid ID.
     */
    public function __construct($nb_site_map = false)
    {
        if ($nb_site_map) {
            $this->transferMixedValue($nb_site_map, 'nb_site_map_id');
        }
        
        parent::__construct();
        $this->__translatedConstruct();
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
        return 'nb_site_map';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_site_map_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_site_map '
                   . "where nb_site_map_id=%nb_site_map_id\$d "
              )
            : null;
    }

    /**
     * Find an instance identified by nb_site_map_key field.
     * @param mixed $nb_site Site that owns Site Map
     * @param string $key Key to search
     * @return CNabuSiteMap Returns a valid instance if exists or null if not.
     */
    public static function findByKey($nb_site, $key)
    {
        $nb_site_id = nb_getMixedValue($nb_site, 'nb_site_id');
        if (is_numeric($nb_site_id)) {
            $retval = CNabuSiteMap::buildObjectFromSQL(
                    'select * '
                    . 'from nb_site_map '
                   . 'where nb_site_id=%site_id$d '
                     . "and nb_site_map_key='%key\$s'",
                    array(
                        'site_id' => $nb_site_id,
                        'key' => $key
                    )
            );
        } else {
            $retval = null;
        }
        
        return $retval;
    }

    /**
     * Get all items in the storage as an associative array where the field 'nb_site_map_id' is the index, and each
     * value is an instance of class CNabuSiteMapBase.
     * @param CNabuSite $nb_site The CNabuSite instance of the Site that owns the Site Map List.
     * @return mixed Returns and array with all items.
     */
    public static function getAllSiteMaps(CNabuSite $nb_site)
    {
        $nb_site_id = nb_getMixedValue($nb_site, 'nb_site_id');
        if (is_numeric($nb_site_id)) {
            $retval = forward_static_call(
            array(get_called_class(), 'buildObjectListFromSQL'),
                'nb_site_map_id',
                'select * '
                . 'from nb_site_map '
               . 'where nb_site_id=%site_id$d',
                array(
                    'site_id' => $nb_site_id
                ),
                $nb_site
            );
        } else {
            $retval = new CNabuSiteMapList();
        }
        
        return $retval;
    }

    /**
     * Gets a filtered list of Site Map instances represented as an array. Params allows the capability of select a
     * subset of fields, order by concrete fields, or truncate the list by a number of rows starting in an offset.
     * @throws \nabu\core\exceptions\ENabuCoreException Raises an exception if $fields or $order have invalid values.
     * @param mixed $nb_site Site instance, object containing a Site Id field or an Id.
     * @param string $q Query string to filter results using a context index.
     * @param string|array $fields List of fields to put in the results.
     * @param string|array $order List of fields to order the results. Each field can be suffixed with "ASC" or "DESC"
     * to determine the short order
     * @param int $offset Offset of first row in the results having the first row at offset 0.
     * @param int $num_items Number of continue rows to get as maximum in the results.
     * @return array Returns an array with all rows found using the criteria.
     */
    public static function getFilteredSiteMapList($nb_site, $q = null, $fields = null, $order = null, $offset = 0, $num_items = 0)
    {
        $nb_site_id = nb_getMixedValue($nb_customer, NABU_SITE_FIELD_ID);
        if (is_numeric($nb_site_id)) {
            $fields_part = nb_prefixFieldList(CNabuSiteMapBase::getStorageName(), $fields, false, true, '`');
            $order_part = nb_prefixFieldList(CNabuSiteMapBase::getStorageName(), $fields, false, false, '`');
        
            if ($num_items !== 0) {
                $limit_part = ($offset > 0 ? $offset . ', ' : '') . $num_items;
            } else {
                $limit_part = false;
            }
        
            $nb_item_list = CNabuEngine::getEngine()->getMainDB()->getQueryAsArray(
                "select " . ($fields_part ? $fields_part . ' ' : '* ')
                . 'from nb_site_map '
               . 'where ' . NABU_SITE_FIELD_ID . '=%site_id$d '
                . ($order_part ? "order by $order_part " : '')
                . ($limit_part ? "limit $limit_part" : ''),
                array(
                    'site_id' => $nb_site_id
                )
            );
        } else {
            $nb_item_list = null;
        }
        
        return $nb_item_list;
    }

    /**
     * Check if the instance passed as parameter $translation is a valid child translation for this object
     * @param INabuTranslation $translation Translation instance to check
     * @return bool Return true if a valid object is passed as instance or false elsewhere
     */
    protected function checkForValidTranslationInstance($translation)
    {
        return ($translation !== null &&
                $translation instanceof CNabuSiteMapLanguage &&
                $translation->matchValue($this, 'nb_site_map_id')
        );
    }

    /**
     * Get all language instances corresponding to available translations.
     * @param bool $force If true force to reload languages list from storage.
     * @return null|array Return an array of \nabu\data\lang\CNabuLanguage instances if they have translations or null
     * if not.
     */
    public function getLanguages($force = false)
    {
        if (!CNabuEngine::getEngine()->isOperationModeStandalone() &&
            ($this->languages_list->getSize() === 0 || $force)
        ) {
            $this->languages_list = CNabuSiteMapLanguage::getLanguagesForTranslatedObject($this);
        }
        
        return $this->languages_list;
    }

    /**
     * Gets available translation instances.
     * @param bool $force If true force to reload translations list from storage.
     * @return null|array Return an array of \nabu\data\site\CNabuSiteMapLanguage instances if they have translations
     * or null if not.
     */
    public function getTranslations($force = false)
    {
        if (!CNabuEngine::getEngine()->isOperationModeStandalone() &&
            ($this->translations_list->getSize() === 0 || $force)
        ) {
            $this->translations_list = CNabuSiteMapLanguage::getTranslationsForTranslatedObject($this);
        }
        
        return $this->translations_list;
    }

    /**
     * Creates a new translation instance. I the translation already exists then replaces ancient translation with this
     * new.
     * @param int|string|CNabuDataObject $nb_language A valid Id or object containing a nb_language_id field to
     * identify the language of new translation.
     * @return CNabuSiteMapLanguage Returns the created instance to store translation or null if not valid language was
     * provided.
     */
    public function newTranslation($nb_language)
    {
        $nb_language_id = nb_getMixedValue($nb_language, NABU_LANG_FIELD_ID);
        if (is_numeric($nb_language_id) || nb_isValidGUID($nb_language_id)) {
            $nb_translation = $this->isBuiltIn()
                            ? new CNabuBuiltInSiteMapLanguage()
                            : new CNabuSiteMapLanguage()
            ;
            $nb_translation->transferValue($this, 'nb_site_map_id');
            $nb_translation->transferValue($nb_language, NABU_LANG_FIELD_ID);
            $this->setTranslation($nb_translation);
        } else {
            $nb_translation = null;
        }
        
        return $nb_translation;
    }

    /**
     * Overrides refresh method to add translations branch to refresh.
     * @return bool Returns true if transations are empty or refreshed.
     */
    public function refresh()
    {
        return parent::refresh() && $this->appendTranslatedRefresh();
    }

    /**
     * Get Site Map Id attribute value
     * @return int Returns the Site Map Id value
     */
    public function getId()
    {
        return $this->getValue('nb_site_map_id');
    }

    /**
     * Sets the Site Map Id attribute value
     * @param int $id New value for attribute
     * @return CNabuSiteMapBase Returns $this
     */
    public function setId($id)
    {
        if ($id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$id")
            );
        }
        $this->setValue('nb_site_map_id', $id);
        
        return $this;
    }

    /**
     * Get Site Id attribute value
     * @return int Returns the Site Id value
     */
    public function getSiteId()
    {
        return $this->getValue('nb_site_id');
    }

    /**
     * Sets the Site Id attribute value
     * @param int $nb_site_id New value for attribute
     * @return CNabuSiteMapBase Returns $this
     */
    public function setSiteId($nb_site_id)
    {
        if ($nb_site_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_site_id")
            );
        }
        $this->setValue('nb_site_id', $nb_site_id);
        
        return $this;
    }

    /**
     * Get Site Map Parent Id attribute value
     * @return null|int Returns the Site Map Parent Id value
     */
    public function getParentId()
    {
        return $this->getValue('nb_site_map_parent_id');
    }

    /**
     * Sets the Site Map Parent Id attribute value
     * @param null|int $parent_id New value for attribute
     * @return CNabuSiteMapBase Returns $this
     */
    public function setParentId($parent_id)
    {
        $this->setValue('nb_site_map_parent_id', $parent_id);
        
        return $this;
    }

    /**
     * Get Site Map Order attribute value
     * @return int Returns the Site Map Order value
     */
    public function getOrder()
    {
        return $this->getValue('nb_site_map_order');
    }

    /**
     * Sets the Site Map Order attribute value
     * @param int $order New value for attribute
     * @return CNabuSiteMapBase Returns $this
     */
    public function setOrder($order)
    {
        if ($order === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$order")
            );
        }
        $this->setValue('nb_site_map_order', $order);
        
        return $this;
    }

    /**
     * Get Site Map Customer Required attribute value
     * @return string Returns the Site Map Customer Required value
     */
    public function getCustomerRequired()
    {
        return $this->getValue('nb_site_map_customer_required');
    }

    /**
     * Sets the Site Map Customer Required attribute value
     * @param string $customer_required New value for attribute
     * @return CNabuSiteMapBase Returns $this
     */
    public function setCustomerRequired($customer_required)
    {
        if ($customer_required === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$customer_required")
            );
        }
        $this->setValue('nb_site_map_customer_required', $customer_required);
        
        return $this;
    }

    /**
     * Get Site Map Level attribute value
     * @return int Returns the Site Map Level value
     */
    public function getLevel()
    {
        return $this->getValue('nb_site_map_level');
    }

    /**
     * Sets the Site Map Level attribute value
     * @param int $level New value for attribute
     * @return CNabuSiteMapBase Returns $this
     */
    public function setLevel($level)
    {
        if ($level === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$level")
            );
        }
        $this->setValue('nb_site_map_level', $level);
        
        return $this;
    }

    /**
     * Get Site Map Next Sibling attribute value
     * @return null|int Returns the Site Map Next Sibling value
     */
    public function getNextSibling()
    {
        return $this->getValue('nb_site_map_next_sibling');
    }

    /**
     * Sets the Site Map Next Sibling attribute value
     * @param null|int $next_sibling New value for attribute
     * @return CNabuSiteMapBase Returns $this
     */
    public function setNextSibling($next_sibling)
    {
        $this->setValue('nb_site_map_next_sibling', $next_sibling);
        
        return $this;
    }

    /**
     * Get Site Target Id attribute value
     * @return null|int Returns the Site Target Id value
     */
    public function getSiteTargetId()
    {
        return $this->getValue('nb_site_target_id');
    }

    /**
     * Sets the Site Target Id attribute value
     * @param null|int $nb_site_target_id New value for attribute
     * @return CNabuSiteMapBase Returns $this
     */
    public function setSiteTargetId($nb_site_target_id)
    {
        $this->setValue('nb_site_target_id', $nb_site_target_id);
        
        return $this;
    }

    /**
     * Get Site Map Use URI attribute value
     * @return string Returns the Site Map Use URI value
     */
    public function getUseURI()
    {
        return $this->getValue('nb_site_map_use_uri');
    }

    /**
     * Sets the Site Map Use URI attribute value
     * @param string $use_uri New value for attribute
     * @return CNabuSiteMapBase Returns $this
     */
    public function setUseURI($use_uri)
    {
        if ($use_uri === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$use_uri")
            );
        }
        $this->setValue('nb_site_map_use_uri', $use_uri);
        
        return $this;
    }

    /**
     * Get Site Map Open Popup attribute value
     * @return string Returns the Site Map Open Popup value
     */
    public function getOpenPopup()
    {
        return $this->getValue('nb_site_map_open_popup');
    }

    /**
     * Sets the Site Map Open Popup attribute value
     * @param string $open_popup New value for attribute
     * @return CNabuSiteMapBase Returns $this
     */
    public function setOpenPopup($open_popup)
    {
        if ($open_popup === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$open_popup")
            );
        }
        $this->setValue('nb_site_map_open_popup', $open_popup);
        
        return $this;
    }

    /**
     * Get Site Map Key attribute value
     * @return null|string Returns the Site Map Key value
     */
    public function getKey()
    {
        return $this->getValue('nb_site_map_key');
    }

    /**
     * Sets the Site Map Key attribute value
     * @param null|string $key New value for attribute
     * @return CNabuSiteMapBase Returns $this
     */
    public function setKey($key)
    {
        $this->setValue('nb_site_map_key', $key);
        
        return $this;
    }

    /**
     * Get Site Map Visible attribute value
     * @return string Returns the Site Map Visible value
     */
    public function getVisible()
    {
        return $this->getValue('nb_site_map_visible');
    }

    /**
     * Sets the Site Map Visible attribute value
     * @param string $visible New value for attribute
     * @return CNabuSiteMapBase Returns $this
     */
    public function setVisible($visible)
    {
        if ($visible === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$visible")
            );
        }
        $this->setValue('nb_site_map_visible', $visible);
        
        return $this;
    }

    /**
     * Get Site Map Separator attribute value
     * @return string Returns the Site Map Separator value
     */
    public function getSeparator()
    {
        return $this->getValue('nb_site_map_separator');
    }

    /**
     * Sets the Site Map Separator attribute value
     * @param string $separator New value for attribute
     * @return CNabuSiteMapBase Returns $this
     */
    public function setSeparator($separator)
    {
        if ($separator === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$separator")
            );
        }
        $this->setValue('nb_site_map_separator', $separator);
        
        return $this;
    }

    /**
     * Get Site Map Tree Position attribute value
     * @return null|int Returns the Site Map Tree Position value
     */
    public function getTreePosition()
    {
        return $this->getValue('nb_site_map_tree_position');
    }

    /**
     * Sets the Site Map Tree Position attribute value
     * @param null|int $tree_position New value for attribute
     * @return CNabuSiteMapBase Returns $this
     */
    public function setTreePosition($tree_position)
    {
        $this->setValue('nb_site_map_tree_position', $tree_position);
        
        return $this;
    }

    /**
     * Get Site Map Icon attribute value
     * @return null|string Returns the Site Map Icon value
     */
    public function getIcon()
    {
        return $this->getValue('nb_site_map_icon');
    }

    /**
     * Sets the Site Map Icon attribute value
     * @param null|string $icon New value for attribute
     * @return CNabuSiteMapBase Returns $this
     */
    public function setIcon($icon)
    {
        $this->setValue('nb_site_map_icon', $icon);
        
        return $this;
    }

    /**
     * Get Site Map CSS Class attribute value
     * @return null|string Returns the Site Map CSS Class value
     */
    public function getCSSClass()
    {
        return $this->getValue('nb_site_map_css_class');
    }

    /**
     * Sets the Site Map CSS Class attribute value
     * @param null|string $css_class New value for attribute
     * @return CNabuSiteMapBase Returns $this
     */
    public function setCSSClass($css_class)
    {
        $this->setValue('nb_site_map_css_class', $css_class);
        
        return $this;
    }

    /**
     * Overrides this method to add support to traits and/or attributes.
     * @param int|CNabuDataObject $nb_language Instance or Id of the language to be used.
     * @param bool $dataonly Render only field values and ommit class control flags.
     * @return array Returns a multilevel associative array with all data.
     */
    public function getTreeData($nb_language = null, $dataonly = false)
    {
        $trdata = parent::getTreeData($nb_language, $dataonly);
        
        $trdata = $this->appendTranslatedTreeData($trdata, $nb_language, $dataonly);
        
        return $trdata;
    }
}
