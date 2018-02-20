<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2018/02/20 09:39:21 UTC
 * ===========================================================================
 * Copyright 2009-2011 Rafael Gutierrez Martinez
 * Copyright 2012-2013 Welma WEB MKT LABS, S.L.
 * Copyright 2014-2016 Where Ideas Simply Come True, S.L.
 * Copyright 2017-2018 nabu-3 Group
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

namespace nabu\visual\site\base;

use \nabu\core\CNabuEngine;
use \nabu\core\exceptions\ENabuCoreException;
use \nabu\data\CNabuDataObject;
use \nabu\data\site\traits\TNabuSiteChild;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity Site Visual Editor Item stored in the storage named nb_site_visual_editor_item.
 * @version 3.0.12 Surface
 * @package \nabu\visual\site\base
 */
abstract class CNabuSiteVisualEditorItemBase extends CNabuDBInternalObject
{
    use TNabuSiteChild;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_site An instance of CNabuSiteVisualEditorItemBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_site_id, or a valid ID.
     * @param mixed $nb_site_visual_editor_item_vr An instance of CNabuSiteVisualEditorItemBase or another object
     * descending from \nabu\data\CNabuDataObject which contains a field named nb_site_visual_editor_item_vr_id, or a
     * valid ID.
     */
    public function __construct($nb_site = false, $nb_site_visual_editor_item_vr = false)
    {
        if ($nb_site) {
            $this->transferMixedValue($nb_site, 'nb_site_id');
        }
        
        if ($nb_site_visual_editor_item_vr) {
            $this->transferMixedValue($nb_site_visual_editor_item_vr, 'nb_site_visual_editor_item_vr_id');
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
        return 'nb_site_visual_editor_item';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_site_id') && $this->isValueString('nb_site_visual_editor_item_vr_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_site_visual_editor_item '
                   . "where nb_site_id=%nb_site_id\$d "
                     . "and nb_site_visual_editor_item_vr_id='%nb_site_visual_editor_item_vr_id\$s' "
              )
            : null;
    }

    /**
     * Gets a filtered list of Site Visual Editor Item instances represented as an array. Params allows the capability
     * of select a subset of fields, order by concrete fields, or truncate the list by a number of rows starting in an
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
    public static function getFilteredSiteVisualEditorItemList($q = null, $fields = null, $order = null, $offset = 0, $num_items = 0)
    {
        $fields_part = nb_prefixFieldList(CNabuSiteVisualEditorItemBase::getStorageName(), $fields, false, true, '`');
        $order_part = nb_prefixFieldList(CNabuSiteVisualEditorItemBase::getStorageName(), $fields, false, false, '`');
        
        if ($num_items !== 0) {
            $limit_part = ($offset > 0 ? $offset . ', ' : '') . $num_items;
        } else {
            $limit_part = false;
        }
        
        $nb_item_list = CNabuEngine::getEngine()->getMainDB()->getQueryAsArray(
            "select " . ($fields_part ? $fields_part . ' ' : '* ')
            . 'from nb_site_visual_editor_item '
            . ($order_part ? "order by $order_part " : '')
            . ($limit_part ? "limit $limit_part" : ''),
            array(
            )
        );
        
        return $nb_item_list;
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
     * Get Site Visual Editor Item VR Id attribute value
     * @return string Returns the Site Visual Editor Item VR Id value
     */
    public function getVRId() : string
    {
        return $this->getValue('nb_site_visual_editor_item_vr_id');
    }

    /**
     * Sets the Site Visual Editor Item VR Id attribute value.
     * @param string $vr_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setVRId(string $vr_id) : CNabuDataObject
    {
        if ($vr_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$vr_id")
            );
        }
        $this->setValue('nb_site_visual_editor_item_vr_id', $vr_id);
        
        return $this;
    }

    /**
     * Get Site Visual Editor Item X attribute value
     * @return null|int Returns the Site Visual Editor Item X value
     */
    public function getX()
    {
        return $this->getValue('nb_site_visual_editor_item_x');
    }

    /**
     * Sets the Site Visual Editor Item X attribute value.
     * @param int|null $x New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setX(int $x = null) : CNabuDataObject
    {
        $this->setValue('nb_site_visual_editor_item_x', $x);
        
        return $this;
    }

    /**
     * Get Site Visual Editor Item Y attribute value
     * @return null|int Returns the Site Visual Editor Item Y value
     */
    public function getY()
    {
        return $this->getValue('nb_site_visual_editor_item_y');
    }

    /**
     * Sets the Site Visual Editor Item Y attribute value.
     * @param int|null $y New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setY(int $y = null) : CNabuDataObject
    {
        $this->setValue('nb_site_visual_editor_item_y', $y);
        
        return $this;
    }

    /**
     * Get Site Visual Editor Item Width attribute value
     * @return null|int Returns the Site Visual Editor Item Width value
     */
    public function getWidth()
    {
        return $this->getValue('nb_site_visual_editor_item_width');
    }

    /**
     * Sets the Site Visual Editor Item Width attribute value.
     * @param int|null $width New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setWidth(int $width = null) : CNabuDataObject
    {
        $this->setValue('nb_site_visual_editor_item_width', $width);
        
        return $this;
    }

    /**
     * Get Site Visual Editor Item Height attribute value
     * @return null|int Returns the Site Visual Editor Item Height value
     */
    public function getHeight()
    {
        return $this->getValue('nb_site_visual_editor_item_height');
    }

    /**
     * Sets the Site Visual Editor Item Height attribute value.
     * @param int|null $height New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setHeight(int $height = null) : CNabuDataObject
    {
        $this->setValue('nb_site_visual_editor_item_height', $height);
        
        return $this;
    }

    /**
     * Get Site Visual Editor Item Points attribute value
     * @return null|string Returns the Site Visual Editor Item Points value
     */
    public function getPoints()
    {
        return $this->getValue('nb_site_visual_editor_item_points');
    }

    /**
     * Sets the Site Visual Editor Item Points attribute value.
     * @param string|null $points New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setPoints(string $points = null) : CNabuDataObject
    {
        $this->setValue('nb_site_visual_editor_item_points', $points);
        
        return $this;
    }
}
