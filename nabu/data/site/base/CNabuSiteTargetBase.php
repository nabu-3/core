<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2019/10/10 11:55:56 UTC
 * ===========================================================================
 * Copyright 2009-2011 Rafael Gutierrez Martinez
 * Copyright 2012-2013 Welma WEB MKT LABS, S.L.
 * Copyright 2014-2016 Where Ideas Simply Come True, S.L.
 * Copyright 2017-2019 nabu-3 Group
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
use \nabu\core\interfaces\INabuHashed;
use \nabu\core\traits\TNabuHashed;
use \nabu\data\CNabuDataObject;
use \nabu\data\lang\interfaces\INabuTranslated;
use \nabu\data\lang\interfaces\INabuTranslation;
use \nabu\data\lang\traits\TNabuTranslated;
use \nabu\data\site\builtin\CNabuBuiltInSiteTargetLanguage;
use \nabu\data\site\CNabuSite;
use \nabu\data\site\CNabuSiteTarget;
use \nabu\data\site\CNabuSiteTargetLanguage;
use \nabu\data\site\CNabuSiteTargetLanguageList;
use \nabu\data\site\CNabuSiteTargetList;
use \nabu\data\site\traits\TNabuSiteChild;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity Site Target stored in the storage named nb_site_target.
 * @author Rafael Gutiérrez <rgutierrez@nabu-3.com>
 * @version 3.0.12 Surface
 * @package \nabu\data\site\base
 */
abstract class CNabuSiteTargetBase extends CNabuDBInternalObject implements INabuTranslated, INabuHashed
{
    use TNabuHashed;
    use TNabuSiteChild;
    use TNabuTranslated;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_site_target An instance of CNabuSiteTargetBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_site_target_id, or a valid ID.
     */
    public function __construct($nb_site_target = false)
    {
        if ($nb_site_target) {
            $this->transferMixedValue($nb_site_target, 'nb_site_target_id');
        }
        
        parent::__construct();
        $this->__translatedConstruct();
        $this->translations_list = new CNabuSiteTargetLanguageList();
    }

    /**
     * Overrides refresh method to add translations branch to refresh.
     * @param bool $force Forces to reload entities from the database storage.
     * @param bool $cascade Forces to reload child entities from the database storage.
     * @return bool Returns true if transations are empty or refreshed.
     */
    public function refresh(bool $force = false, bool $cascade = false) : bool
    {
        return parent::refresh($force, $cascade) && $this->appendTranslatedRefresh($force);
    }

    /**
     * Overrides delete method to delete translations when delete the this instance.
     * @return bool Returns true if the entity and their translations are deleted.
     */
    public function delete() : bool
    {
        $this->deleteTranslations(true);
        
        return parent::delete();
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
        return 'nb_site_target';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_site_target_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_site_target '
                   . "where nb_site_target_id=%nb_site_target_id\$d "
              )
            : null;
    }

    /**
     * Find an instance identified by nb_site_target_hash field.
     * @param string $hash Hash to search
     * @return CNabuDataObject Returns a valid instance if exists or null if not.
     */
    public static function findByHash(string $hash)
    {
        return CNabuSiteTarget::buildObjectFromSQL(
                'select * '
                . 'from nb_site_target '
               . "where nb_site_target_hash='%hash\$s'",
                array(
                    'hash' => $hash
                )
        );
    }

    /**
     * Find an instance identified by nb_site_target_key field.
     * @param mixed $nb_site Site that owns Site Target
     * @param string $key Key to search
     * @return CNabuSiteTarget Returns a valid instance if exists or null if not.
     */
    public static function findByKey($nb_site, $key)
    {
        $nb_site_id = nb_getMixedValue($nb_site, 'nb_site_id');
        if (is_numeric($nb_site_id)) {
            $retval = CNabuSiteTarget::buildObjectFromSQL(
                    'select * '
                    . 'from nb_site_target '
                   . 'where nb_site_id=%site_id$d '
                     . "and nb_site_target_key='%key\$s'",
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
     * Get all items in the storage as an associative array where the field 'nb_site_target_id' is the index, and each
     * value is an instance of class CNabuSiteTargetBase.
     * @param CNabuSite $nb_site The CNabuSite instance of the Site that owns the Site Target List.
     * @return mixed Returns and array with all items.
     */
    public static function getAllSiteTargets(CNabuSite $nb_site)
    {
        $nb_site_id = nb_getMixedValue($nb_site, 'nb_site_id');
        if (is_numeric($nb_site_id)) {
            $retval = forward_static_call(
            array(get_called_class(), 'buildObjectListFromSQL'),
                'nb_site_target_id',
                'select * '
                . 'from nb_site_target '
               . 'where nb_site_id=%site_id$d',
                array(
                    'site_id' => $nb_site_id
                ),
                $nb_site
            );
        } else {
            $retval = new CNabuSiteTargetList();
        }
        
        return $retval;
    }

    /**
     * Gets a filtered list of Site Target instances represented as an array. Params allows the capability of select a
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
    public static function getFilteredSiteTargetList($nb_site, $q = null, $fields = null, $order = null, $offset = 0, $num_items = 0)
    {
        $nb_site_id = nb_getMixedValue($nb_customer, NABU_SITE_FIELD_ID);
        if (is_numeric($nb_site_id)) {
            $fields_part = nb_prefixFieldList(CNabuSiteTargetBase::getStorageName(), $fields, false, true, '`');
            $order_part = nb_prefixFieldList(CNabuSiteTargetBase::getStorageName(), $fields, false, false, '`');
        
            if ($num_items !== 0) {
                $limit_part = ($offset > 0 ? $offset . ', ' : '') . $num_items;
            } else {
                $limit_part = false;
            }
        
            $nb_item_list = CNabuEngine::getEngine()->getMainDB()->getQueryAsArray(
                "select " . ($fields_part ? $fields_part . ' ' : '* ')
                . 'from nb_site_target '
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
                $translation instanceof CNabuSiteTargetLanguage &&
                $translation->matchValue($this, 'nb_site_target_id')
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
            $this->languages_list = CNabuSiteTargetLanguage::getLanguagesForTranslatedObject($this);
        }
        
        return $this->languages_list;
    }

    /**
     * Gets available translation instances.
     * @param bool $force If true force to reload translations list from storage.
     * @return null|array Return an array of \nabu\data\site\CNabuSiteTargetLanguage instances if they have
     * translations or null if not.
     */
    public function getTranslations($force = false)
    {
        if (!CNabuEngine::getEngine()->isOperationModeStandalone() &&
            ($this->translations_list->getSize() === 0 || $force)
        ) {
            $this->translations_list = CNabuSiteTargetLanguage::getTranslationsForTranslatedObject($this);
        }
        
        return $this->translations_list;
    }

    /**
     * Creates a new translation instance. I the translation already exists then replaces ancient translation with this
     * new.
     * @param int|string|CNabuDataObject $nb_language A valid Id or object containing a nb_language_id field to
     * identify the language of new translation.
     * @return CNabuSiteTargetLanguage Returns the created instance to store translation or null if not valid language
     * was provided.
     */
    public function newTranslation($nb_language)
    {
        $nb_language_id = nb_getMixedValue($nb_language, NABU_LANG_FIELD_ID);
        if (is_numeric($nb_language_id) || nb_isValidGUID($nb_language_id)) {
            $nb_translation = $this->isBuiltIn()
                            ? new CNabuBuiltInSiteTargetLanguage()
                            : new CNabuSiteTargetLanguage()
            ;
            $nb_translation->transferValue($this, 'nb_site_target_id');
            $nb_translation->transferValue($nb_language, NABU_LANG_FIELD_ID);
            $this->setTranslation($nb_translation);
        } else {
            $nb_translation = null;
        }
        
        return $nb_translation;
    }

    /**
     * Get Site Target Id attribute value
     * @return int Returns the Site Target Id value
     */
    public function getId() : int
    {
        return $this->getValue('nb_site_target_id');
    }

    /**
     * Sets the Site Target Id attribute value.
     * @param int $id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setId(int $id) : CNabuDataObject
    {
        if ($id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$id")
            );
        }
        $this->setValue('nb_site_target_id', $id);
        
        return $this;
    }

    /**
     * Get Site Id attribute value
     * @return int Returns the Site Id value
     */
    public function getSiteId() : int
    {
        return $this->getValue('nb_site_id');
    }

    /**
     * Sets the Site Id attribute value.
     * @param int $nb_site_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setSiteId(int $nb_site_id) : CNabuDataObject
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
     * Get Site Target Hash attribute value
     * @return null|string Returns the Site Target Hash value
     */
    public function getHash()
    {
        return $this->getValue('nb_site_target_hash');
    }

    /**
     * Sets the Site Target Hash attribute value.
     * @param string|null $hash New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setHash(string $hash = null) : CNabuDataObject
    {
        $this->setValue('nb_site_target_hash', $hash);
        
        return $this;
    }

    /**
     * Get Site Target Order attribute value
     * @return int Returns the Site Target Order value
     */
    public function getOrder() : int
    {
        return $this->getValue('nb_site_target_order');
    }

    /**
     * Sets the Site Target Order attribute value.
     * @param int $order New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setOrder(int $order) : CNabuDataObject
    {
        if ($order === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$order")
            );
        }
        $this->setValue('nb_site_target_order', $order);
        
        return $this;
    }

    /**
     * Get Site Target Creation Datetime attribute value
     * @return mixed Returns the Site Target Creation Datetime value
     */
    public function getCreationDatetime()
    {
        return $this->getValue('nb_site_target_creation_datetime');
    }

    /**
     * Sets the Site Target Creation Datetime attribute value.
     * @param mixed $creation_datetime New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setCreationDatetime($creation_datetime) : CNabuDataObject
    {
        if ($creation_datetime === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$creation_datetime")
            );
        }
        $this->setValue('nb_site_target_creation_datetime', $creation_datetime);
        
        return $this;
    }

    /**
     * Get Site Target Last Update Datetime attribute value
     * @return mixed Returns the Site Target Last Update Datetime value
     */
    public function getLastUpdateDatetime()
    {
        return $this->getValue('nb_site_target_last_update_datetime');
    }

    /**
     * Sets the Site Target Last Update Datetime attribute value.
     * @param mixed $last_update_datetime New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setLastUpdateDatetime($last_update_datetime) : CNabuDataObject
    {
        $this->setValue('nb_site_target_last_update_datetime', $last_update_datetime);
        
        return $this;
    }

    /**
     * Get Site Target Begin Date attribute value
     * @return mixed Returns the Site Target Begin Date value
     */
    public function getBeginDate()
    {
        return $this->getValue('nb_site_target_begin_date');
    }

    /**
     * Sets the Site Target Begin Date attribute value.
     * @param mixed $begin_date New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setBeginDate($begin_date) : CNabuDataObject
    {
        $this->setValue('nb_site_target_begin_date', $begin_date);
        
        return $this;
    }

    /**
     * Get Site Target URL Filter attribute value
     * @return null|string Returns the Site Target URL Filter value
     */
    public function getURLFilter()
    {
        return $this->getValue('nb_site_target_url_filter');
    }

    /**
     * Sets the Site Target URL Filter attribute value.
     * @param string|null $url_filter New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setURLFilter(string $url_filter = null) : CNabuDataObject
    {
        $this->setValue('nb_site_target_url_filter', $url_filter);
        
        return $this;
    }

    /**
     * Get Site Target Key attribute value
     * @return null|string Returns the Site Target Key value
     */
    public function getKey()
    {
        return $this->getValue('nb_site_target_key');
    }

    /**
     * Sets the Site Target Key attribute value.
     * @param string|null $key New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setKey(string $key = null) : CNabuDataObject
    {
        $this->setValue('nb_site_target_key', $key);
        
        return $this;
    }

    /**
     * Get Site Target Zone attribute value
     * @return null|string Returns the Site Target Zone value
     */
    public function getZone()
    {
        return $this->getValue('nb_site_target_zone');
    }

    /**
     * Sets the Site Target Zone attribute value.
     * @param string|null $zone New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setZone(string $zone = "B") : CNabuDataObject
    {
        $this->setValue('nb_site_target_zone', $zone);
        
        return $this;
    }

    /**
     * Get Site Target Use HTTP attribute value
     * @return string Returns the Site Target Use HTTP value
     */
    public function getUseHTTP() : string
    {
        return $this->getValue('nb_site_target_use_http');
    }

    /**
     * Sets the Site Target Use HTTP attribute value.
     * @param string $use_http New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setUseHTTP(string $use_http = "F") : CNabuDataObject
    {
        if ($use_http === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$use_http")
            );
        }
        $this->setValue('nb_site_target_use_http', $use_http);
        
        return $this;
    }

    /**
     * Get Site Target Use HTTPS attribute value
     * @return string Returns the Site Target Use HTTPS value
     */
    public function getUseHTTPS() : string
    {
        return $this->getValue('nb_site_target_use_https');
    }

    /**
     * Sets the Site Target Use HTTPS attribute value.
     * @param string $use_https New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setUseHTTPS(string $use_https = "F") : CNabuDataObject
    {
        if ($use_https === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$use_https")
            );
        }
        $this->setValue('nb_site_target_use_https', $use_https);
        
        return $this;
    }

    /**
     * Get MIMEType Id attribute value
     * @return null|string Returns the MIMEType Id value
     */
    public function getMIMETypeId()
    {
        return $this->getValue('nb_mimetype_id');
    }

    /**
     * Sets the MIMEType Id attribute value.
     * @param string|null $nb_mimetype_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setMIMETypeId(string $nb_mimetype_id = null) : CNabuDataObject
    {
        $this->setValue('nb_mimetype_id', $nb_mimetype_id);
        
        return $this;
    }

    /**
     * Get Site Target Attachment attribute value
     * @return string Returns the Site Target Attachment value
     */
    public function getAttachment() : string
    {
        return $this->getValue('nb_site_target_attachment');
    }

    /**
     * Sets the Site Target Attachment attribute value.
     * @param string $attachment New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setAttachment(string $attachment = "F") : CNabuDataObject
    {
        if ($attachment === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$attachment")
            );
        }
        $this->setValue('nb_site_target_attachment', $attachment);
        
        return $this;
    }

    /**
     * Get Site Target Output Type attribute value
     * @return null|string Returns the Site Target Output Type value
     */
    public function getOutputType()
    {
        return $this->getValue('nb_site_target_output_type');
    }

    /**
     * Sets the Site Target Output Type attribute value.
     * @param string|null $output_type New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setOutputType(string $output_type = "HTML") : CNabuDataObject
    {
        $this->setValue('nb_site_target_output_type', $output_type);
        
        return $this;
    }

    /**
     * Get Site Target Render Interface attribute value
     * @return null|string Returns the Site Target Render Interface value
     */
    public function getRenderInterface()
    {
        return $this->getValue('nb_site_target_render_interface');
    }

    /**
     * Sets the Site Target Render Interface attribute value.
     * @param string|null $render_interface New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setRenderInterface(string $render_interface = null) : CNabuDataObject
    {
        $this->setValue('nb_site_target_render_interface', $render_interface);
        
        return $this;
    }

    /**
     * Get Site Target Transform Interface attribute value
     * @return null|string Returns the Site Target Transform Interface value
     */
    public function getTransformInterface()
    {
        return $this->getValue('nb_site_target_transform_interface');
    }

    /**
     * Sets the Site Target Transform Interface attribute value.
     * @param string|null $transform_interface New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setTransformInterface(string $transform_interface = null) : CNabuDataObject
    {
        $this->setValue('nb_site_target_transform_interface', $transform_interface);
        
        return $this;
    }

    /**
     * Get Site Target Smarty Display File attribute value
     * @return null|string Returns the Site Target Smarty Display File value
     */
    public function getSmartyDisplayFile()
    {
        return $this->getValue('nb_site_target_smarty_display_file');
    }

    /**
     * Sets the Site Target Smarty Display File attribute value.
     * @param string|null $smarty_display_file New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setSmartyDisplayFile(string $smarty_display_file = null) : CNabuDataObject
    {
        $this->setValue('nb_site_target_smarty_display_file', $smarty_display_file);
        
        return $this;
    }

    /**
     * Get Site Target Smarty Content File attribute value
     * @return null|string Returns the Site Target Smarty Content File value
     */
    public function getSmartyContentFile()
    {
        return $this->getValue('nb_site_target_smarty_content_file');
    }

    /**
     * Sets the Site Target Smarty Content File attribute value.
     * @param string|null $smarty_content_file New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setSmartyContentFile(string $smarty_content_file = null) : CNabuDataObject
    {
        $this->setValue('nb_site_target_smarty_content_file', $smarty_content_file);
        
        return $this;
    }

    /**
     * Get Site Target Smarty Debugging attribute value
     * @return string Returns the Site Target Smarty Debugging value
     */
    public function getSmartyDebugging() : string
    {
        return $this->getValue('nb_site_target_smarty_debugging');
    }

    /**
     * Sets the Site Target Smarty Debugging attribute value.
     * @param string $smarty_debugging New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setSmartyDebugging(string $smarty_debugging = "F") : CNabuDataObject
    {
        if ($smarty_debugging === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$smarty_debugging")
            );
        }
        $this->setValue('nb_site_target_smarty_debugging', $smarty_debugging);
        
        return $this;
    }

    /**
     * Get Site Target Plugin Name attribute value
     * @return null|string Returns the Site Target Plugin Name value
     */
    public function getPluginName()
    {
        return $this->getValue('nb_site_target_plugin_name');
    }

    /**
     * Sets the Site Target Plugin Name attribute value.
     * @param string|null $plugin_name New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setPluginName(string $plugin_name = null) : CNabuDataObject
    {
        $this->setValue('nb_site_target_plugin_name', $plugin_name);
        
        return $this;
    }

    /**
     * Get Site Target PHP Trace attribute value
     * @return string Returns the Site Target PHP Trace value
     */
    public function getPHPTrace() : string
    {
        return $this->getValue('nb_site_target_php_trace');
    }

    /**
     * Sets the Site Target PHP Trace attribute value.
     * @param string $php_trace New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setPHPTrace(string $php_trace = "F") : CNabuDataObject
    {
        if ($php_trace === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$php_trace")
            );
        }
        $this->setValue('nb_site_target_php_trace', $php_trace);
        
        return $this;
    }

    /**
     * Get Site Target Ignore Policies attribute value
     * @return string Returns the Site Target Ignore Policies value
     */
    public function getIgnorePolicies() : string
    {
        return $this->getValue('nb_site_target_ignore_policies');
    }

    /**
     * Sets the Site Target Ignore Policies attribute value.
     * @param string $ignore_policies New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setIgnorePolicies(string $ignore_policies = "F") : CNabuDataObject
    {
        if ($ignore_policies === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$ignore_policies")
            );
        }
        $this->setValue('nb_site_target_ignore_policies', $ignore_policies);
        
        return $this;
    }

    /**
     * Get Site Target Use Commerce attribute value
     * @return string Returns the Site Target Use Commerce value
     */
    public function getUseCommerce() : string
    {
        return $this->getValue('nb_site_target_use_commerce');
    }

    /**
     * Sets the Site Target Use Commerce attribute value.
     * @param string $use_commerce New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setUseCommerce(string $use_commerce = "F") : CNabuDataObject
    {
        if ($use_commerce === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$use_commerce")
            );
        }
        $this->setValue('nb_site_target_use_commerce', $use_commerce);
        
        return $this;
    }

    /**
     * Get Site Target Use Apps attribute value
     * @return string Returns the Site Target Use Apps value
     */
    public function getUseApps() : string
    {
        return $this->getValue('nb_site_target_use_apps');
    }

    /**
     * Sets the Site Target Use Apps attribute value.
     * @param string $use_apps New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setUseApps(string $use_apps = "F") : CNabuDataObject
    {
        if ($use_apps === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$use_apps")
            );
        }
        $this->setValue('nb_site_target_use_apps', $use_apps);
        
        return $this;
    }

    /**
     * Get Site Target Dynamic Cache Control attribute value
     * @return string Returns the Site Target Dynamic Cache Control value
     */
    public function getDynamicCacheControl() : string
    {
        return $this->getValue('nb_site_target_dynamic_cache_control');
    }

    /**
     * Sets the Site Target Dynamic Cache Control attribute value.
     * @param string $dynamic_cache_control New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setDynamicCacheControl(string $dynamic_cache_control = "S") : CNabuDataObject
    {
        if ($dynamic_cache_control === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$dynamic_cache_control")
            );
        }
        $this->setValue('nb_site_target_dynamic_cache_control', $dynamic_cache_control);
        
        return $this;
    }

    /**
     * Get Site Target Dynamic Cache Max Age attribute value
     * @return null|int Returns the Site Target Dynamic Cache Max Age value
     */
    public function getDynamicCacheMaxAge()
    {
        return $this->getValue('nb_site_target_dynamic_cache_max_age');
    }

    /**
     * Sets the Site Target Dynamic Cache Max Age attribute value.
     * @param int|null $dynamic_cache_max_age New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setDynamicCacheMaxAge(int $dynamic_cache_max_age = null) : CNabuDataObject
    {
        $this->setValue('nb_site_target_dynamic_cache_max_age', $dynamic_cache_max_age);
        
        return $this;
    }

    /**
     * Get Site Target Script File attribute value
     * @return null|string Returns the Site Target Script File value
     */
    public function getScriptFile()
    {
        return $this->getValue('nb_site_target_script_file');
    }

    /**
     * Sets the Site Target Script File attribute value.
     * @param string|null $script_file New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setScriptFile(string $script_file = null) : CNabuDataObject
    {
        $this->setValue('nb_site_target_script_file', $script_file);
        
        return $this;
    }

    /**
     * Get Site Target CSS File attribute value
     * @return null|string Returns the Site Target CSS File value
     */
    public function getCSSFile()
    {
        return $this->getValue('nb_site_target_css_file');
    }

    /**
     * Sets the Site Target CSS File attribute value.
     * @param string|null $css_file New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setCSSFile(string $css_file = null) : CNabuDataObject
    {
        $this->setValue('nb_site_target_css_file', $css_file);
        
        return $this;
    }

    /**
     * Get Site Target CSS Class attribute value
     * @return null|string Returns the Site Target CSS Class value
     */
    public function getCSSClass()
    {
        return $this->getValue('nb_site_target_css_class');
    }

    /**
     * Sets the Site Target CSS Class attribute value.
     * @param string|null $css_class New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setCSSClass(string $css_class = null) : CNabuDataObject
    {
        $this->setValue('nb_site_target_css_class', $css_class);
        
        return $this;
    }

    /**
     * Get Site Target Commands attribute value
     * @return null|string Returns the Site Target Commands value
     */
    public function getCommands()
    {
        return $this->getValue('nb_site_target_commands');
    }

    /**
     * Sets the Site Target Commands attribute value.
     * @param string|null $commands New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setCommands(string $commands = null) : CNabuDataObject
    {
        $this->setValue('nb_site_target_commands', $commands);
        
        return $this;
    }

    /**
     * Get Site Target Meta Robots attribute value
     * @return null|string Returns the Site Target Meta Robots value
     */
    public function getMetaRobots()
    {
        return $this->getValue('nb_site_target_meta_robots');
    }

    /**
     * Sets the Site Target Meta Robots attribute value.
     * @param string|null $meta_robots New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setMetaRobots(string $meta_robots = null) : CNabuDataObject
    {
        $this->setValue('nb_site_target_meta_robots', $meta_robots);
        
        return $this;
    }

    /**
     * Get Site Target Icon attribute value
     * @return null|string Returns the Site Target Icon value
     */
    public function getIcon()
    {
        return $this->getValue('nb_site_target_icon');
    }

    /**
     * Sets the Site Target Icon attribute value.
     * @param string|null $icon New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setIcon(string $icon = null) : CNabuDataObject
    {
        $this->setValue('nb_site_target_icon', $icon);
        
        return $this;
    }

    /**
     * Get Site Target Apps Slot attribute value
     * @return null|string Returns the Site Target Apps Slot value
     */
    public function getAppsSlot()
    {
        return $this->getValue('nb_site_target_apps_slot');
    }

    /**
     * Sets the Site Target Apps Slot attribute value.
     * @param string|null $apps_slot New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setAppsSlot(string $apps_slot = null) : CNabuDataObject
    {
        $this->setValue('nb_site_target_apps_slot', $apps_slot);
        
        return $this;
    }

    /**
     * Get Site Target Attributes attribute value
     * @return null|array Returns the Site Target Attributes value
     */
    public function getAttributes()
    {
        return $this->getValueJSONDecoded('nb_site_target_attributes');
    }

    /**
     * Sets the Site Target Attributes attribute value.
     * @param string|array|null $attributes New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setAttributes($attributes = null) : CNabuDataObject
    {
        $this->setValueJSONEncoded('nb_site_target_attributes', $attributes);
        
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
        
        $trdata['attributes'] = $this->getAttributes();
        $trdata = $this->appendTranslatedTreeData($trdata, $nb_language, $dataonly);
        
        return $trdata;
    }
}
