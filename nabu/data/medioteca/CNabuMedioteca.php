<?php

/*  Copyright 2009-2011 Rafael Gutierrez Martinez
 *  Copyright 2012-2013 Welma WEB MKT LABS, S.L.
 *  Copyright 2014-2016 Where Ideas Simply Come True, S.L.
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */

namespace nabu\data\medioteca;

use \nabu\data\medioteca\base\CNabuMediotecaBase;
use nabu\data\customer\CNabuCustomer;
use nabu\data\medioteca\builtin\CNabuBuiltInMediotecaItem;

/**
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\medioteca
 */
class CNabuMedioteca extends CNabuMediotecaBase
{
    /**
     * Medioteca Items collection
     * @var CNabuMediotecaItemList
     */
    private $nb_medioteca_item_list = null;

    public function __construct($nb_medioteca = false)
    {
        parent::__construct($nb_medioteca);

        $this->nb_medioteca_item_list = new CNabuMediotecaItemList($this);
    }

    /**
     * Gets all items in the Medioteca.
     * @param bool $force If true, clears current items list and retrieves a new list.
     * @return CNabuMediotecaItemList Returns a list object with avaliable items.
     */
    public function getItems($force = false)
    {
        if (!$this->isBuiltIn() && ($this->nb_medioteca_item_list->isEmpty() || $force)) {
            $this->nb_medioteca_item_list = CNabuMediotecaItem::getItemsForMedioteca($this);
        }

        return $this->nb_medioteca_item_list;
    }

    /**
     * Gets an item from the Medioteca by their ID.
     * @param mixed $nb_medioteca_item An instance descending from CNabuDataObject that contains a field named
     * nb_medioteca_item_id or an ID.
     * @return CNabuMediotecaItem If an Item exists, then returns their instance elsewhere returns null.
     */
    public function getItem($nb_medioteca_item)
    {
        $nb_medioteca_item_id = nb_getMixedValue($nb_medioteca_item, 'nb_medioteca_item_id');
        if (is_numeric($nb_medioteca_item_id) || nb_isValidGUID($nb_medioteca_item_id)) {
            return $this->nb_medioteca_item_list->getItem($nb_medioteca_item_id);
        }

        return null;
    }

    /**
     * Gets an item from the Medioteca using their key.
     * @param string $key A key idenfying a possible item in the Medioteca.
     * @return CNabuMediotecaItem If an Item exists, identified by $key, then returns it, elsewhere returns null.
     */
    public function getItemByKey($key)
    {
        return $this->nb_medioteca_item_list->getItem($key, CNabuMediotecaItemList::INDEX_KEY);
    }

    public function newItem($key = null)
    {
        $nb_medioteca_item = $this->isBuiltIn()
                           ? new CNabuBuiltInMediotecaItem()
                           : new CNabuMediotecaItem()
        ;
        $nb_medioteca_item->setKey($key);

        return $this->addItemObject($nb_medioteca_item);
    }

    public function addItemObject(CNabuMediotecaItem $nb_medioteca_item)
    {
        $nb_medioteca_item->setMedioteca($this);

        return $this->nb_medioteca_item_list->addItem($nb_medioteca_item);
    }

    public function indexAll()
    {
        $this->nb_medioteca_item_list->sort();
    }

    public function getMediotecaItemKeysIndex()
    {
        return $this->nb_medioteca_item_list->getIndex(CNabuMediotecaItemList::INDEX_KEY);
    }

    /**
     * Overrides getTreeData method to add special branches.
     * If $nb_language have a valid value, also adds a translation object
     * with current translation pointed by it.
     * @param int|object $nb_language Instance or Id of the language to be used.
     * @param bool $dataonly Render only field values and ommit class control flags.
     * @return array Returns a multilevel associative array with all data.
     */
    public function getTreeData($nb_language = null, $dataonly = false)
    {
        $trdata = parent::getTreeData($nb_language, $dataonly);

        $this->getItems();

        $trdata['items'] = $this->nb_medioteca_item_list;
        $trdata['item_keys'] = $this->getMediotecaItemKeysIndex();

        return $trdata;
    }

    static public function getMediotecasForCustomer(CNabuCustomer $nb_customer)
    {
        $nb_customer_id = nb_getMixedValue($nb_customer, NABU_CUSTOMER_FIELD_ID);
        if (is_numeric($nb_customer_id)) {
            $retval = CNabuMedioteca::buildObjectListFromSQL(
                NABU_MEDIOTECA_FIELD_ID,
                'select * '
                . 'from ' . NABU_MEDIOTECA_TABLE . ' '
                . 'where nb_customer_id=%cust_id$d',
                array(
                    'cust_id' => $nb_customer_id
                )
            );
            $retval->setCustomer($nb_customer);
        } else {
            $retval = new CNabuMediotecaList($nb_customer);
        }

        return $retval;
    }
}
