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

namespace nabu\data\catalog;
use nabu\data\catalog\base\CNabuCatalogItemBase;
use nabu\data\interfaces\INabuDataObjectTreeNode;
use nabu\data\traits\TNabuDataObjectTreeNode;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\catalog
 */
class CNabuCatalogItem extends CNabuCatalogItemBase implements INabuDataObjectTreeNode
{
    use TNabuDataObjectTreeNode;

    public function __construct($nb_catalog_item = false)
    {
        parent::__construct($nb_catalog_item);
        $this->__treeNodeConstructor();
    }

    public function setCatalog(CNabuCatalog $nb_catalog)
    {
        parent::setCatalog($nb_catalog);

        if ($nb_catalog !== $this->nb_tree_child_list->getCatalog()) {
            $this->nb_tree_child_list = new CNabuCatalogItemTree($nb_catalog);
            $this->nb_tree_child_list->setOwner($this);
        }
    }

    /**
     * Gets the list of items of a catalog from the storage.
     * @param mixed $nb_catalog A CNabuDataObject instance containing a field name nb_catalog_id or a Catalog ID.
     * @param int $level Max deep level to retrieve items. If $level is 0 then retrieves all levels.
     * @return CNabuCatalogItemList Returns a CNabuCatalogItemList with all items found. If no items found, then
     * returns an empty instance.
     */
    static public function getItemsForCatalog($nb_catalog, int $level)
    {
        $nb_catalog_id = nb_getMixedValue($nb_catalog, NABU_CATALOG_FIELD_ID);
        if (is_numeric($nb_catalog_id)) {
            $retval = CNabuCatalogItem::buildObjectListFromSQL(
                'nb_catalog_item_id',
                'select * '
                . 'from nb_catalog_item '
                . 'where nb_catalog_id=%catalog_id$d '
                . ($level > 0 ? 'and nb_catalog_item_level<=%level$d ' : '')
                . 'order by nb_catalog_item_order asc',
                array(
                    'catalog_id' => $nb_catalog_id,
                    'level' => $level
                ),
                ($nb_catalog instanceof CNabuCatalog ? $nb_catalog : null)
            );
            $retval->setCatalog($nb_catalog);
        } else {
            $retval = new CNabuCatalogItemList($nb_catalog);
            $retval->setOwner($this);
        }

        return $retval;
    }

    /**
     * Gets the list of translations associated to all items of a catalog from the storage.
     * @param mixed $nb_catalog A CNabuDataObject instance containing a field name nb_catalog_id or a Catalog ID.
     * @param int $level Max deep level to retrieve items. If $level is 0 then retrieves all levels.
     * @return CNabuCatalogItemLanguageList Returns a CNabuCatalogItemLanguageList with all translations found.
     * If no translations found, then rturns an empty instance.
     */
    static public function getItemTranslationsForCatalog($nb_catalog, int $level)
    {
        $nb_catalog_id = nb_getMixedValue($nb_catalog, NABU_CATALOG_FIELD_ID);
        if (is_numeric($nb_catalog_id)) {
            $retval = CNabuCatalogItemLanguage::buildObjectListFromSQL(
                null,
                'select cil.* '
                . 'from nb_catalog_item ci, nb_catalog_item_lang cil '
               . 'where ci.nb_catalog_item_id=cil.nb_catalog_item_id '
                 . 'and ci.nb_catalog_id=%catalog_id$d '
                 . ($level > 0 ? 'and ci.nb_catalog_item_level<=%level$d ' : '')
               . 'order by ci.nb_catalog_item_order, cil.nb_language_id',
                array(
                    'catalog_id' => $nb_catalog_id,
                    'level' => $level
                )
            );
        } else {
            $retval = new CNabuCatalogItemLanguageList();
        }

        return $retval;
    }

    public function getItems($force = false)
    {
        if ($this->nb_tree_child_list->isEmpty() || $force) {
            $this->nb_tree_child_list->merge(
                CNabuCatalogItem::buildObjectListFromSQL(
                    'nb_catalog_item_id',
                    'select * '
                    . 'from nb_catalog_item '
                    . 'where nb_catalog_id=%catalog_id$d '
                    . 'and nb_catalog_item_parent_id=%parent_id$d '
                    . 'order by nb_catalog_item_order asc',
                    array(
                        'catalog_id' => $this->getCatalogId(),
                        'parent_id' => $this->getId()
                    ),
                    $this->getCatalog()
                )
            );
        }

        return $this->nb_tree_child_list;
    }

    /**
     * Populates this Item and deeper levels until max $deep deep.
     * @param int $deep Deep level to scan and populate the subtree.
     * @return int Returns the number of populated mediotecas.
     */
    public function populateMediotecas(int $deep = 0)
    {
        if (is_object($nb_catalog = $this->getCatalog()) &&
            is_object($nb_customer = $nb_catalog->getCustomer())
        ) {
            $this->getMedioteca($nb_customer);
        }
    }

    /* === Below, methods implemented to act as a INabuDataObjectTreeNode === */
    public function createChildContainer()
    {
        $nb_catalog_item_tree = new CNabuCatalogItemTree($this->getCatalog());
        $nb_catalog_item_tree->setOwner($this);

        return $nb_catalog_item_tree;
    }

    public function getTreeData($nb_language = null, $dataonly = false)
    {
        $trdata = parent::getTreeData($nb_language, $dataonly);
        $this->__treeNodeGetTreeData($trdata, $nb_language, $dataonly);

        return $trdata;
    }
    /* === End of INabuDataObjectTreeNode methods implementation === */
}
