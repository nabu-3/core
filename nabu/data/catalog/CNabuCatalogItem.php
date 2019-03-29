<?php

/** @license
 *  Copyright 2019-2011 Rafael Gutierrez Martinez
 *  Copyright 2012-2013 Welma WEB MKT LABS, S.L.
 *  Copyright 2014-2016 Where Ideas Simply Come True, S.L.
 *  Copyright 2017 nabu-3 Group
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
use nabu\core\exceptions\ENabuCoreException;
use nabu\data\catalog\base\CNabuCatalogItemBase;
use nabu\data\catalog\exceptions\ENabuCatalogException;
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
                NABU_CATALOG_ITEM_FIELD_ID,
                'SELECT *
                   FROM nb_catalog_item
                  WHERE nb_catalog_id=%catalog_id$d '
                . ($level > 0 ? 'AND nb_catalog_item_level<=%level$d ' : '')
                . 'ORDER BY nb_catalog_item_order ASC',
                array(
                    'catalog_id' => $nb_catalog_id,
                    'level' => $level
                ),
                ($nb_catalog instanceof CNabuCatalog ? $nb_catalog : null)
            );
            if ($nb_catalog instanceof CNabuCatalog) {
                $retval->setCatalog($nb_catalog);
            }
        } else {
            if ($nb_catalog instanceof CNabuCatalog) {
                $retval = new CNabuCatalogItemList($nb_catalog);
                $retval->setOwner($nb_catalog);
            } else  {
                $retval = new CNabuCatalogItemList();
            }
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
                'SELECT cil.*
                   FROM nb_catalog_item ci, nb_catalog_item_lang cil
                  WHERE ci.nb_catalog_item_id=cil.nb_catalog_item_id
                    AND ci.nb_catalog_id=%catalog_id$d '
                 . ($level > 0 ? 'and ci.nb_catalog_item_level<=%level$d ' : '')
               . 'ORDER BY ci.nb_catalog_item_order, cil.nb_language_id',
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

    /**
     * Get children items of this Item.
     * @param bool $force If true, then refresh the list from the database storage.
     * @return CNabuCatalogItemList Returns a Catalog Item List object with children items.
     */
    public function getItems(bool $force = false) : CNabuCatalogItemList
    {
        if ($this->nb_tree_child_list->isEmpty() || $force) {
            $this->nb_tree_child_list->merge(
                CNabuCatalogItem::buildObjectListFromSQL(
                    'nb_catalog_item_id',
                    'SELECT *
                       FROM nb_catalog_item
                      WHERE nb_catalog_id=%catalog_id$d
                        AND nb_catalog_item_parent_id=%parent_id$d
                      ORDER BY nb_catalog_item_order ASC',
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

    /**
     * Moves a Catalog Item to place it in front of another Item in the same Catalog.
     * @param mixed $nb_before_item An Id or a CNabuDataObject instance containing a field named nb_catalog_item_id
     * representing the Item where $nb_catalog_item will be putted in front of.
     * @return bool Returns true if the Item was moved or false if not.
     * @throws ENabuCatalogException Raises an exception if some of requested Items does not corresponding to this Catalog.
     * @throws ENabuCoreException Raises an exception if $nb_before_item have an invalid value.
     */
    public function moveBefore($nb_before_item)
    {
        return CNabuCatalogItem::moveItemBeforeItem($this->getCatalog(), $this, $nb_before_item);
    }

    /**
     * Moves a Catalog Item to place it in front of another Item in the same Catalog.
     * @param CNabuCatalog $nb_catalog The Catalog in which the movement will be made.
     * @param mixed $nb_catalog_item An Id or a CNabuDataObject instance containing a field named nb_catalog_item_id
     * to be moved.
     * @param mixed $nb_before_item An Id or a CNabuDataObject instance containing a field named nb_catalog_item_id
     * representing the Item where $nb_catalog_item will be putted in front of.
     * @return bool Returns true if the Item was moved or false if not.
     * @throws ENabuCatalogException Raises an exception if some of requested Items does not corresponding to this Catalog.
     * @throws ENabuCoreException Raises an exception if $nb_catalog_item or $nb_before_item have an invalid value.
     */
    public static function moveItemBeforeItem(CNabuCatalog $nb_catalog, $nb_catalog_item, $nb_before_item)
    {
        $retval = false;

        $nb_catalog_id = $nb_catalog->getId();
        $nb_catalog_item_id = nb_getMixedValue($nb_catalog_item, NABU_CATALOG_ITEM_FIELD_ID);
        if (is_numeric($nb_catalog_item_id)) {
            $nb_catalog_before_id = nb_getMixedValue($nb_before_item, NABU_CATALOG_ITEM_FIELD_ID);
            if (is_numeric($nb_catalog_before_id)) {
                $nb_catalog->relinkDB();
                $db = $nb_catalog->getDB();
                $data = $db->getQueryAsAssoc(
                    NABU_CATALOG_ITEM_FIELD_ID,
                    'SELECT nb_catalog_item_id, nb_catalog_item_order, nb_catalog_item_level, nb_catalog_item_next_sibling
                       FROM nb_catalog_item
                      WHERE nb_catalog_item_id IN (%item_id$d, %before_id$d)
                        AND nb_catalog_id = %cat_id$d',
                    array(
                        'cat_id' => $nb_catalog_id,
                        'item_id' => $nb_catalog_item_id,
                        'before_id' => $nb_catalog_before_id
                    )
                );
                if (count($data) === 2) {
                    if ($data[$nb_catalog_item_id]['nb_catalog_item_next_sibling'] !== $data[$nb_catalog_before_id]['nb_catalog_item_order']) {
                        if ($data[$nb_catalog_item_id]['nb_catalog_item_order'] < $data[$nb_catalog_before_id]['nb_catalog_item_order']) {
                            $db->executeUpdate(
                                'UPDATE nb_catalog_item AS ci1, nb_catalog_item AS ci2, nb_catalog_item AS ci3,
                                        (SELECT nb_catalog_id, last_sibling
                                           FROM (SELECT ci11.nb_catalog_id, max(ci11.nb_catalog_item_next_sibling) last_sibling
                                                   FROM nb_catalog_item ci10, nb_catalog_item ci11
                                                  WHERE ci10.nb_catalog_id=%cat_id$d
                                                    AND ci10.nb_catalog_id=ci11.nb_catalog_id
                                                    AND ci10.nb_catalog_item_id = %item_id$d
                                                    AND ci10.nb_catalog_item_level >= ci11.nb_catalog_item_level
                                                    AND ci10.nb_catalog_item_order >= ci11.nb_catalog_item_order
                                                    AND ci11.nb_catalog_item_next_sibling IS NOT NULL
                                                    AND ci10.nb_catalog_item_order BETWEEN ci11.nb_catalog_item_order AND ci11.nb_catalog_item_next_sibling - 1
                                                  GROUP BY ci11.nb_catalog_id, ci11.nb_catalog_item_level
                                                  UNION ALL
                                                 SELECT nb_catalog_id, max(nb_catalog_item_order) + 1
                                                   FROM nb_catalog_item
                                                  WHERE nb_catalog_id=%cat_id$d
                                                  GROUP BY nb_catalog_id) AS t
                                          ORDER BY last_sibling ASC
                                          LIMIT 1) AS ci4
                                    SET ci3.nb_catalog_item_parent_id =
                                            if (ci3.nb_catalog_item_id = ci1.nb_catalog_item_id,
                                                ci2.nb_catalog_item_parent_id,
                                                ci3.nb_catalog_item_parent_id
                                            ),
                                        ci3.nb_catalog_item_level =
                                            if (ci3.nb_catalog_item_order BETWEEN ci1.nb_catalog_item_order AND ci1.nb_catalog_item_next_sibling - 1,
                                                ci3.nb_catalog_item_level + ci2.nb_catalog_item_level - ci1.nb_catalog_item_level,
                                                ci3.nb_catalog_item_level
                                            ),
                                        ci3.nb_catalog_item_order =
                                            if (ci3.nb_catalog_item_order BETWEEN ci1.nb_catalog_item_order AND ci4.last_sibling - 1,
                                                ci2.nb_catalog_item_order + ci3.nb_catalog_item_order - ci4.last_sibling,
                                                if (ci3.nb_catalog_item_order BETWEEN ci4.last_sibling AND ci2.nb_catalog_item_order - 1,
                                                    ci3.nb_catalog_item_order + ci1.nb_catalog_item_order - ci4.last_sibling,
                                                    ci3.nb_catalog_item_order
                                                )
                                            ),
                                        ci3.nb_catalog_item_next_sibling =
                                            if (ci3.nb_catalog_item_id = ci1.nb_catalog_item_id,
                                                ci2.nb_catalog_item_order,
                                                if (ci3.nb_catalog_item_next_sibling = ci1.nb_catalog_item_order,
                                                    if (ci1.nb_catalog_item_next_sibling IS NULL, NULL, ci1.nb_catalog_item_order),
                                                    if (ci3.nb_catalog_item_next_sibling BETWEEN ci1.nb_catalog_item_order AND ci1.nb_catalog_item_next_sibling - 1,
                                                        ci2.nb_catalog_item_order + ci3.nb_catalog_item_next_sibling - ci4.last_sibling,
                                                        if (ci3.nb_catalog_item_next_sibling BETWEEN ci1.nb_catalog_item_next_sibling AND ci2.nb_catalog_item_order,
                                                            ci3.nb_catalog_item_next_sibling + ci1.nb_catalog_item_order - ci4.last_sibling,
                                                            ci3.nb_catalog_item_next_sibling
                                                        )
                                                    )
                                                )
                                            )
                                  WHERE ci1.nb_catalog_item_id=%item_id$d
                                    AND ci2.nb_catalog_item_id=%before_id$d
                                    AND ci1.nb_catalog_id=ci2.nb_catalog_id
                                    AND ci1.nb_catalog_id=ci3.nb_catalog_id
                                    AND ci1.nb_catalog_id=ci4.nb_catalog_id',
                                array(
                                    'cat_id' => $nb_catalog_id,
                                    'item_id' => $nb_catalog_item_id,
                                    'before_id' => $nb_catalog_before_id
                                )
                            );
                            $retval = true;
                        } elseif ($data[$nb_catalog_item_id]['nb_catalog_item_order'] > $data[$nb_catalog_before_id]['nb_catalog_item_order']) {
                            $db->executeUpdate(
                                'UPDATE nb_catalog_item AS ci1, nb_catalog_item AS ci2, nb_catalog_item AS ci3,
                                        (SELECT nb_catalog_id, last_sibling
                                           FROM (SELECT ci11.nb_catalog_id, max(ci11.nb_catalog_item_next_sibling) AS last_sibling
                                                   FROM nb_catalog_item ci10, nb_catalog_item ci11
                                                  WHERE ci10.nb_catalog_id=%cat_id$d
                                                    AND ci10.nb_catalog_id=ci11.nb_catalog_id
                                                    AND ci10.nb_catalog_item_id = %item_id$d
                                                    AND ci10.nb_catalog_item_level >= ci11.nb_catalog_item_level
                                                    AND ci10.nb_catalog_item_order >= ci11.nb_catalog_item_order
                                                    AND ci11.nb_catalog_item_next_sibling IS NOT NULL
                                                    AND ci10.nb_catalog_item_order BETWEEN ci11.nb_catalog_item_order AND ci11.nb_catalog_item_next_sibling - 1
                                                  GROUP BY ci11.nb_catalog_id, ci11.nb_catalog_item_level
                                                  UNION ALL
                                                 SELECT nb_catalog_id, max(nb_catalog_item_order) + 1
                                                   FROM nb_catalog_item
                                                  WHERE nb_catalog_id=%cat_id$d
                                                  GROUP BY nb_catalog_id) AS t
                                          ORDER BY last_sibling ASC
                                          LIMIT 1) AS ci4
                                    SET ci3.nb_catalog_item_parent_id =
                                        if (ci3.nb_catalog_item_id = ci1.nb_catalog_item_id,
                                            ci2.nb_catalog_item_parent_id,
                                            ci3.nb_catalog_item_parent_id
                                        ),
                                        ci3.nb_catalog_item_level =
                                            if (ci3.nb_catalog_item_order BETWEEN ci1.nb_catalog_item_order AND ci4.last_sibling - 1,
                                                ci3.nb_catalog_item_level + ci2.nb_catalog_item_level - ci1.nb_catalog_item_level,
                                                ci3.nb_catalog_item_level
                                            ),
                                        ci3.nb_catalog_item_order =
                                        if (ci3.nb_catalog_item_order BETWEEN ci1.nb_catalog_item_order AND ci4.last_sibling - 1,
                                            ci2.nb_catalog_item_order + ci3.nb_catalog_item_order - ci1.nb_catalog_item_order,
                                            if (ci3.nb_catalog_item_order BETWEEN ci2.nb_catalog_item_order AND ci1.nb_catalog_item_order - 1,
                                                ci3.nb_catalog_item_order + ci4.last_sibling - ci1.nb_catalog_item_order,
                                                ci3.nb_catalog_item_order
                                            )
                                        ),
                                        ci3.nb_catalog_item_next_sibling =
                                        if (ci3.nb_catalog_item_id = ci1.nb_catalog_item_id,
                                            ci2.nb_catalog_item_order + ci4.last_sibling - ci1.nb_catalog_item_order,
                                            if (ci3.nb_catalog_item_next_sibling = ci1.nb_catalog_item_order,
                                                ci1.nb_catalog_item_next_sibling,
                                                if (ci3.nb_catalog_item_next_sibling BETWEEN ci1.nb_catalog_item_order AND ci1.nb_catalog_item_next_sibling - 1,
                                                    ci2.nb_catalog_item_order + ci3.nb_catalog_item_next_sibling - ci1.nb_catalog_item_order,
                                                    if (ci3.nb_catalog_item_next_sibling BETWEEN ci2.nb_catalog_item_order + 1 AND ci1.nb_catalog_item_order - 1,
                                                        ci3.nb_catalog_item_next_sibling + ci4.last_sibling - ci1.nb_catalog_item_order,
                                                        ci3.nb_catalog_item_next_sibling
                                                    )
                                                )
                                            )
                                        )
                                  WHERE ci1.nb_catalog_item_id=%item_id$d
                                    AND ci1.nb_catalog_id=ci2.nb_catalog_id
                                    AND ci2.nb_catalog_item_id=%before_id$d
                                    AND ci1.nb_catalog_id=ci3.nb_catalog_id
                                    AND ci1.nb_catalog_id=ci4.nb_catalog_id',
                                array(
                                    'cat_id' => $nb_catalog_id,
                                    'item_id' => $nb_catalog_item_id,
                                    'before_id' => $nb_catalog_before_id
                                )
                            );
                            $retval = true;
                        }
                    }
                } elseif (count($data) === 1) {
                    if ($nb_catalog_item_id !== $nb_catalog_before_id) {
                        $id = array_keys($data)[0];
                        throw new ENabuCatalogException(
                            ENabuCatalogException::ERROR_ITEM_NOT_INCLUDED_IN_CATALOG,
                            array($id === $nb_catalog_item_id ? $nb_catalog_before_id : $nb_catalog_item_id)
                        );
                    }
                } else {
                    throw new ENabuCatalogException(
                        ENabuCatalogException::ERROR_ITEM_NOT_INCLUDED_IN_CATALOG,
                        array(implode(', ', array_keys($data)))
                    );
                }
            } else {
                throw new ENabuCoreException(
                    ENabuCoreException::ERROR_UNEXPECTED_PARAM_VALUE,
                    array(print_r($nb_before_item, true), '$nb_before_item')
                );
            }
        } else {
            throw new ENabuCoreException(
                ENabuCoreException::ERROR_UNEXPECTED_PARAM_VALUE,
                array(print_r($nb_catalog_item, true), '$nb_catalog_item')
            );
        }

        return $retval;
    }

    /**
     * Moves a Catalog Item to place it after another Item in the same Catalog.
     * @param mixed $nb_after_item An Id or a CNabuDataObject instance containing a field named nb_catalog_item_id
     * representing the Item where $nb_catalog_item will be putted after of.
     * @return bool Returns true if the Item was moved or false if not.
     * @throws ENabuCatalogException Raises an exception if some of requested Items does not corresponding to this Catalog.
     * @throws ENabuCoreException Raises an exception if $nb_after_item have an invalid value.
     */
    public function moveAfter($nb_after_item)
    {
        return CNabuCatalogItem::moveItemAfterItem($this->getCatalog(), $this, $nb_after_item);
    }

    /**
     * Moves a Catalog Item to place it after another Item in the same Catalog.
     * @param CNabuCatalog $nb_catalog The Catalog in which the movement will be made.
     * @param mixed $nb_catalog_item An Id or a CNabuDataObject instance containing a field named nb_catalog_item_id
     * to be moved.
     * @param mixed $nb_after_item An Id or a CNabuDataObject instance containing a field named nb_catalog_item_id
     * representing the Item where $nb_catalog_item will be putted after of.
     * @return bool Returns true if the Item was moved or false if not.
     * @throws ENabuCatalogException Raises an exception if some of requested Items does not corresponding to this Catalog.
     * @throws ENabuCoreException Raises an exception if $nb_catalog_item or $nb_after_item have an invalid value.
     */
    public static function moveItemAfterItem(CNabuCatalog $nb_catalog, $nb_catalog_item, $nb_after_item)
    {
        $retval = false;

        $nb_catalog_id = $nb_catalog->getId();
        $nb_catalog_item_id = nb_getMixedValue($nb_catalog_item, NABU_CATALOG_ITEM_FIELD_ID);
        if (is_numeric($nb_catalog_item_id)) {
            $nb_catalog_after_id = nb_getMixedValue($nb_after_item, NABU_CATALOG_ITEM_FIELD_ID);
            if (is_numeric($nb_catalog_after_id)) {
                $nb_catalog->relinkDB();
                $db = $nb_catalog->getDB();
                $data = $db->getQueryAsAssoc(
                    NABU_CATALOG_ITEM_FIELD_ID,
                    'SELECT nb_catalog_item_id, nb_catalog_item_order, nb_catalog_item_level, nb_catalog_item_next_sibling
                       FROM nb_catalog_item
                      WHERE nb_catalog_item_id IN (%item_id$d, %after_id$d)
                        AND nb_catalog_id = %cat_id$d',
                    array(
                        'cat_id' => $nb_catalog_id,
                        'item_id' => $nb_catalog_item_id,
                        'after_id' => $nb_catalog_after_id
                    )
                );
                if (count($data) === 2) {
                    if ($data[$nb_catalog_after_id]['nb_catalog_item_next_sibling'] !== $data[$nb_catalog_item_id]['nb_catalog_item_order']) {
                        if ($data[$nb_catalog_item_id]['nb_catalog_item_order'] < $data[$nb_catalog_after_id]['nb_catalog_item_order']) {
                            $db->executeUpdate(
                                'UPDATE nb_catalog_item AS ci1, nb_catalog_item AS ci2, nb_catalog_item AS ci3,
	                                    (SELECT nb_catalog_id, last_sibling
                                           FROM (SELECT ci11.nb_catalog_id, max(ci11.nb_catalog_item_next_sibling) last_sibling
                                                   FROM nb_catalog_item ci10, nb_catalog_item ci11
                                                  WHERE ci10.nb_catalog_id=%cat_id$d
                                                    AND ci10.nb_catalog_id=ci11.nb_catalog_id
                                                    AND ci10.nb_catalog_item_id = %item_id$d
                                                    AND ci10.nb_catalog_item_level >= ci11.nb_catalog_item_level
                                                    AND ci10.nb_catalog_item_order >= ci11.nb_catalog_item_order
                                                    AND ci11.nb_catalog_item_next_sibling IS NOT NULL
                                                    AND ci10.nb_catalog_item_order BETWEEN ci11.nb_catalog_item_order AND ci11.nb_catalog_item_next_sibling - 1
                                                  GROUP BY ci11.nb_catalog_id, ci11.nb_catalog_item_level
                                                  UNION ALL
                                                 SELECT nb_catalog_id, max(nb_catalog_item_order) + 1
                                                   FROM nb_catalog_item
				                                  WHERE nb_catalog_id=%cat_id$d
                                                  GROUP BY nb_catalog_id) AS t
                                          ORDER BY last_sibling ASC
                                          LIMIT 1) AS ci4,
	                                    (SELECT nb_catalog_id, last_sibling
                                           FROM (SELECT ci11.nb_catalog_id, max(ci11.nb_catalog_item_next_sibling) last_sibling
                                                   FROM nb_catalog_item ci10, nb_catalog_item ci11
                                                  WHERE ci10.nb_catalog_id=%cat_id$d
                                                    AND ci10.nb_catalog_id=ci11.nb_catalog_id
                                                    AND ci10.nb_catalog_item_id = %after_id$d
                                                    AND ci10.nb_catalog_item_level >= ci11.nb_catalog_item_level
                                                    AND ci10.nb_catalog_item_order >= ci11.nb_catalog_item_order
                                                    AND ci11.nb_catalog_item_next_sibling IS NOT NULL
                                                    AND ci10.nb_catalog_item_order BETWEEN ci11.nb_catalog_item_order AND ci11.nb_catalog_item_next_sibling - 1
                                                  GROUP BY ci11.nb_catalog_id, ci11.nb_catalog_item_level
                                                  UNION ALL
                                                 SELECT nb_catalog_id, max(nb_catalog_item_order) + 1
                                                   FROM nb_catalog_item
				                                  WHERE nb_catalog_id=%cat_id$d
                                                  GROUP BY nb_catalog_id) AS t
                                          ORDER BY last_sibling ASC
                                          LIMIT 1) AS ci5
                                    SET ci3.nb_catalog_item_parent_id =
                                        if(ci3.nb_catalog_item_id = ci1.nb_catalog_item_id,
                                            ci2.nb_catalog_item_parent_id,
                                            ci3.nb_catalog_item_parent_id
                                        ),
                                        ci3.nb_catalog_item_level =
                                        if (ci3.nb_catalog_item_order BETWEEN ci1.nb_catalog_item_order AND ci4.last_sibling - 1,
                                            ci3.nb_catalog_item_level + ci2.nb_catalog_item_level - ci1.nb_catalog_item_level,
                                            ci3.nb_catalog_item_level
                                        ),
	                                    ci3.nb_catalog_item_order =
                                            if (ci3.nb_catalog_item_order BETWEEN ci1.nb_catalog_item_order AND ci4.last_sibling - 1,
                                                ci5.last_sibling + ci3.nb_catalog_item_order - ci4.last_sibling,
                                                if(ci3.nb_catalog_item_order BETWEEN ci4.last_sibling AND ci5.last_sibling - 1,
                                                    ci3.nb_catalog_item_order + ci1.nb_catalog_item_order - ci4.last_sibling,
                                                    ci3.nb_catalog_item_order
                                                )
                                            ),
                                        ci3.nb_catalog_item_next_sibling =
                                            if (ci3.nb_catalog_item_id = ci1.nb_catalog_item_id,
                                                ci2.nb_catalog_item_next_sibling,
                                                if (ci3.nb_catalog_item_id = ci2.nb_catalog_item_id,
                                                    ci5.last_sibling - ci4.last_sibling + ci1.nb_catalog_item_order,
                                                    if (ci3.nb_catalog_item_next_sibling BETWEEN ci1.nb_catalog_item_order + 1 AND ci4.last_sibling - 1,
                                                        ci5.last_sibling + ci3.nb_catalog_item_next_sibling - ci4.last_sibling,
                                                        if (ci3.nb_catalog_item_next_sibling BETWEEN ci4.last_sibling AND ci5.last_sibling - 1,
                                                            ci3.nb_catalog_item_next_sibling + ci1.nb_catalog_item_order - ci4.last_sibling,
                                                            ci3.nb_catalog_item_next_sibling
                                                        )
                                                    )
                                                )
                                            )
                                  WHERE ci1.nb_catalog_item_id=%item_id$d
                                    AND ci1.nb_catalog_id=ci2.nb_catalog_id
                                    AND ci2.nb_catalog_item_id=%after_id$d
                                    AND ci1.nb_catalog_id=ci3.nb_catalog_id
                                    AND ci1.nb_catalog_id=ci4.nb_catalog_id',
                                array(
                                    'cat_id' => $nb_catalog_id,
                                    'item_id' => $nb_catalog_item_id,
                                    'after_id' => $nb_catalog_after_id
                                )
                            );
                            $retval = true;
                        } elseif ($data[$nb_catalog_item_id]['nb_catalog_item_order'] > $data[$nb_catalog_after_id]['nb_catalog_item_order']) {
                            $db->executeUpdate(
                                'UPDATE nb_catalog_item AS ci1, nb_catalog_item AS ci2, nb_catalog_item AS ci3,
                                        (SELECT nb_catalog_id, last_sibling
                                           FROM (SELECT ci11.nb_catalog_id, max(ci11.nb_catalog_item_next_sibling) AS last_sibling
				                                   FROM nb_catalog_item ci10, nb_catalog_item ci11
				                                  WHERE ci10.nb_catalog_id=%cat_id$d
                                                    AND ci10.nb_catalog_id=ci11.nb_catalog_id
                                                    AND ci10.nb_catalog_item_id=%item_id$d
                                                    AND ci10.nb_catalog_item_level >= ci11.nb_catalog_item_level
                                                    AND ci10.nb_catalog_item_order >= ci11.nb_catalog_item_order
                                                    AND ci11.nb_catalog_item_next_sibling IS NOT NULL
                                                    AND ci10.nb_catalog_item_order BETWEEN ci11.nb_catalog_item_order AND ci11.nb_catalog_item_next_sibling - 1
				                                  GROUP BY ci11.nb_catalog_id, ci11.nb_catalog_item_level
                                                  UNION ALL
		                                         SELECT nb_catalog_id, max(nb_catalog_item_order) + 1
                                                   FROM nb_catalog_item
				                                  WHERE nb_catalog_id=%cat_id$d
                                                  GROUP BY nb_catalog_id) AS t
                                                  ORDER BY last_sibling ASC
                                                  LIMIT 1) AS ci4,
			                            (SELECT nb_catalog_id, last_sibling
                                           FROM (SELECT ci11.nb_catalog_id, max(ci11.nb_catalog_item_next_sibling) AS last_sibling
						                           FROM nb_catalog_item ci10, nb_catalog_item ci11
			                                      WHERE ci10.nb_catalog_id=%cat_id$d
                                                    AND ci10.nb_catalog_id=ci11.nb_catalog_id
                                                    AND ci10.nb_catalog_item_id=%after_id$d
                                                    AND ci10.nb_catalog_item_level >= ci11.nb_catalog_item_level
                                                    AND ci10.nb_catalog_item_order >= ci11.nb_catalog_item_order
                                                    AND ci11.nb_catalog_item_next_sibling IS NOT NULL
                                                    AND ci10.nb_catalog_item_order BETWEEN ci11.nb_catalog_item_order AND ci11.nb_catalog_item_next_sibling - 1
		                                          GROUP BY ci11.nb_catalog_id, ci11.nb_catalog_item_level
                                                  UNION ALL
	                                             SELECT nb_catalog_id, max(nb_catalog_item_order) + 1
                                                   FROM nb_catalog_item
		                                          WHERE nb_catalog_id=%cat_id$d
                                                  GROUP BY nb_catalog_id) AS t
                                           ORDER BY last_sibling ASC
                                           LIMIT 1) AS ci5
                                    SET ci3.nb_catalog_item_parent_id =
                                            if (ci3.nb_catalog_item_id = ci1.nb_catalog_item_id,
                                                ci2.nb_catalog_item_parent_id,
                                                ci3.nb_catalog_item_parent_id
                                            ),
                                        ci3.nb_catalog_item_level =
                                            if (ci3.nb_catalog_item_order BETWEEN ci1.nb_catalog_item_order AND ci4.last_sibling - 1,
                                                ci2.nb_catalog_item_level + ci3.nb_catalog_item_level - ci1.nb_catalog_item_level,
                                                ci3.nb_catalog_item_level
                                            ),
                                        ci3.nb_catalog_item_order =
                                            if (ci3.nb_catalog_item_order BETWEEN ci1.nb_catalog_item_order AND ci4.last_sibling - 1,
                                                ci5.last_sibling + ci3.nb_catalog_item_order - ci1.nb_catalog_item_order,
                                                if (ci3.nb_catalog_item_order BETWEEN ci5.last_sibling AND ci1.nb_catalog_item_order - 1,
                                                    ci3.nb_catalog_item_order + ci4.last_sibling - ci1.nb_catalog_item_order,
                                                    ci3.nb_catalog_item_order
                                                )
                                            ),
                                        ci3.nb_catalog_item_next_sibling =
                                            if (ci3.nb_catalog_item_id = ci2.nb_catalog_item_id,
                                                ci5.last_sibling,
                                                /*if (ci3.nb_catalog_item_id = ci1.nb_catalog_item_id,
                                                    ci2.nb_catalog_item_next_sibling,*/
                                                    if (ci3.nb_catalog_item_next_sibling BETWEEN ci1.nb_catalog_item_order AND ci4.last_sibling,
                                                        ci5.last_sibling + ci3.nb_catalog_item_next_sibling - ci1.nb_catalog_item_order,
                                                        if (ci3.nb_catalog_item_next_sibling BETWEEN ci5.last_sibling AND ci4.last_sibling,
                                                            ci3.nb_catalog_item_next_sibling + ci4.last_sibling - ci1.nb_catalog_item_order,
                                                            ci3.nb_catalog_item_next_sibling
                                                        )
                                                    )
                                                /*)*/
                                            )
                                  WHERE ci1.nb_catalog_item_id=%item_id$d
                                    AND ci2.nb_catalog_item_id=%after_id$d
                                    AND ci1.nb_catalog_id=ci2.nb_catalog_id
                                    AND ci1.nb_catalog_id=ci3.nb_catalog_id
                                    AND ci1.nb_catalog_id=ci4.nb_catalog_id
                                    AND ci1.nb_catalog_id=ci5.nb_catalog_id',
                                array(
                                    'cat_id' => $nb_catalog_id,
                                    'item_id' => $nb_catalog_item_id,
                                    'after_id' => $nb_catalog_after_id
                                )
                            );
                            $retval = true;
                        }
                    }
                } elseif (count($data) === 1) {
                    if ($nb_catalog_item_id !== $nb_catalog_after_id) {
                        $id = array_keys($data)[0];
                        throw new ENabuCatalogException(
                            ENabuCatalogException::ERROR_ITEM_NOT_INCLUDED_IN_CATALOG,
                            array($id === $nb_catalog_item_id ? $nb_catalog_after_id : $nb_catalog_item_id)
                        );
                    }
                } else {
                    throw new ENabuCatalogException(
                        ENabuCatalogException::ERROR_ITEM_NOT_INCLUDED_IN_CATALOG,
                        array(implode(', ', array_keys($data)))
                    );
                }
            } else {
                throw new ENabuCoreException(
                    ENabuCoreException::ERROR_UNEXPECTED_PARAM_VALUE,
                    array(print_r($nb_after_item, true), '$nb_after_item')
                );
            }
        } else {
            throw new ENabuCoreException(
                ENabuCoreException::ERROR_UNEXPECTED_PARAM_VALUE,
                array(print_r($nb_catalog_item, true), '$nb_catalog_item')
            );
        }

        return $retval;
    }

    /**
     * Moves a Catalog Item to place it inside another Item that is empty in the same Catalog.
     * @param mixed $nb_into_item An Id or a CNabuDataObject instance containing a field named nb_catalog_item_id
     * representing the Item where $nb_catalog_item will be putted into.
     * @return bool Returns true if the Item was moved or false if not.
     * @throws ENabuCatalogException Raises an exception if some of requested Items does not corresponding to this Catalog.
     * @throws ENabuCoreException Raises an exception if $nb_after_item have an invalid value.
     */
    public function moveInto($nb_into_item)
    {
        return CNabuCatalogItem::moveItemIntoItem($this->getCatalog(), $this, $nb_into_item);
    }

    /**
     * Moves a Catalog Item to place it into another Item that is empty in the same Catalog.
     * @param CNabuCatalog $nb_catalog The Catalog in which the movement will be made.
     * @param mixed $nb_catalog_item An Id or a CNabuDataObject instance containing a field named nb_catalog_item_id
     * to be moved.
     * @param mixed $nb_into_item An Id or a CNabuDataObject instance containing a field named nb_catalog_item_id
     * representing the Item where $nb_catalog_item will be putted into.
     * @return bool Returns true if the Item was moved or false if not.
     * @throws ENabuCatalogException Raises an exception if some of requested Items does not corresponding to this Catalog.
     * @throws ENabuCoreException Raises an exception if $nb_catalog_item or $nb_after_item have an invalid value.
     */
    public static function moveItemIntoItem(CNabuCatalog $nb_catalog, $nb_catalog_item, $nb_into_item)
    {
        $retval = false;

        $nb_catalog_id = $nb_catalog->getId();
        $nb_catalog_item_id = nb_getMixedValue($nb_catalog_item, NABU_CATALOG_ITEM_FIELD_ID);
        if (is_numeric($nb_catalog_item_id)) {
            $nb_catalog_into_id = nb_getMixedValue($nb_into_item, NABU_CATALOG_ITEM_FIELD_ID);
            if (is_numeric($nb_catalog_into_id)) {
                $nb_catalog->relinkDB();
                $db = $nb_catalog->getDB();
                $data = $db->getQueryAsAssoc(
                    NABU_CATALOG_ITEM_FIELD_ID,
                    'SELECT nb_catalog_item_id, nb_catalog_item_order, nb_catalog_item_level, nb_catalog_item_next_sibling
                       FROM nb_catalog_item
                      WHERE nb_catalog_item_id IN (%item_id$d, %into_id$d)
                        AND nb_catalog_id = %cat_id$d',
                    array(
                        'cat_id' => $nb_catalog_id,
                        'item_id' => $nb_catalog_item_id,
                        'into_id' => $nb_catalog_into_id
                    )
                );
                if (count($data) === 2) {
                    if ($data[$nb_catalog_item_id]['nb_catalog_item_order'] < $data[$nb_catalog_into_id]['nb_catalog_item_order']) {
                        $db->executeUpdate(
                            'UPDATE nb_catalog_item AS ci1, nb_catalog_item AS ci2, nb_catalog_item AS ci3,
                                    (SELECT nb_catalog_id, last_sibling
                                       FROM (SELECT ci11.nb_catalog_id, max(ci11.nb_catalog_item_next_sibling) last_sibling
                               				   FROM nb_catalog_item ci10, nb_catalog_item ci11
                            				  WHERE ci10.nb_catalog_id=%cat_id$d
                                                AND ci10.nb_catalog_id=ci11.nb_catalog_id
                                                AND ci10.nb_catalog_item_id = %item_id$d
                                                AND ci10.nb_catalog_item_level >= ci11.nb_catalog_item_level
                                                AND ci10.nb_catalog_item_order >= ci11.nb_catalog_item_order
                                                AND ci11.nb_catalog_item_next_sibling IS NOT NULL
                                                AND ci10.nb_catalog_item_order BETWEEN ci11.nb_catalog_item_order AND ci11.nb_catalog_item_next_sibling - 1
                            				  GROUP BY ci11.nb_catalog_id, ci11.nb_catalog_item_level
                                              UNION ALL
                            				 SELECT nb_catalog_id, max(nb_catalog_item_order) + 1
                                               FROM nb_catalog_item
                            				  WHERE nb_catalog_id=%cat_id$d
                                              GROUP BY nb_catalog_id) AS t
                                              ORDER BY last_sibling ASC
                                              LIMIT 1
                                            ) AS ci4
                                SET ci3.nb_catalog_item_parent_id =
                                        if(ci3.nb_catalog_item_id = ci1.nb_catalog_item_id,
                                           ci2.nb_catalog_item_id,
                                           ci3.nb_catalog_item_parent_id
                                        ),
                                    ci3.nb_catalog_item_level =
                            	 	    if(ci3.nb_catalog_item_order BETWEEN ci1.nb_catalog_item_order AND ci4.last_sibling - 1,
                            			    ci2.nb_catalog_item_level + ci3.nb_catalog_item_level + 1 - ci1.nb_catalog_item_level,
                            			    ci3.nb_catalog_item_level
                            			),
                            	    ci3.nb_catalog_item_order =
                            		    if(ci3.nb_catalog_item_order BETWEEN ci1.nb_catalog_item_order AND ci4.last_sibling - 1,
                            			    ci2.nb_catalog_item_order + ci3.nb_catalog_item_order + 1 - ci1.nb_catalog_item_order,
                            			    if(ci3.nb_catalog_item_order BETWEEN ci4.last_sibling AND ci2.nb_catalog_item_order,
                            				    ci3.nb_catalog_item_order + ci1.nb_catalog_item_order - ci4.last_sibling,
                            				    ci3.nb_catalog_item_order
                            				)
                            			),
                            	    ci3.nb_catalog_item_next_sibling =
                            		    if(ci3.nb_catalog_item_id = ci1.nb_catalog_item_id,
                            			    NULL,
                            			    if(ci3.nb_catalog_item_next_sibling BETWEEN ci1.nb_catalog_item_order + 1 AND ci4.last_sibling - 1,
                            				    ci2.nb_catalog_item_order + ci3.nb_catalog_item_next_sibling + 1 - ci4.last_sibling,
                            				    if(ci3.nb_catalog_item_next_sibling BETWEEN ci4.last_sibling AND ci2.nb_catalog_item_order,
                            					    ci3.nb_catalog_item_next_sibling + ci1.nb_catalog_item_order - ci4.last_sibling,
                            					    ci3.nb_catalog_item_next_sibling
                            					)
                            				)
                            			)
                              WHERE ci1.nb_catalog_item_id=%item_id$d
                                AND ci2.nb_catalog_item_id=%into_id$d
                                AND ci1.nb_catalog_id=ci2.nb_catalog_id
                                AND ci1.nb_catalog_id=ci3.nb_catalog_id
                                AND ci1.nb_catalog_id=ci4.nb_catalog_id',
                            array(
                                'cat_id' => $nb_catalog_id,
                                'item_id' => $nb_catalog_item_id,
                                'into_id' => $nb_catalog_into_id
                            )
                        );
                        $retval = true;
                    } elseif ($data[$nb_catalog_item_id]['nb_catalog_item_order'] > $data[$nb_catalog_into_id]['nb_catalog_item_order']) {
                        $db->executeUpdate(
                            'UPDATE nb_catalog_item AS ci1, nb_catalog_item AS ci2, nb_catalog_item AS ci3,
                                    (SELECT nb_catalog_id, last_sibling
                                       FROM (SELECT ci11.nb_catalog_id, max(ci11.nb_catalog_item_next_sibling) last_sibling
				                               FROM nb_catalog_item ci10, nb_catalog_item ci11
				                              WHERE ci10.nb_catalog_id=%cat_id$d
                                                AND ci10.nb_catalog_id=ci11.nb_catalog_id
                                                AND ci10.nb_catalog_item_id = %item_id$d
                                                AND ci10.nb_catalog_item_level >= ci11.nb_catalog_item_level
                                                AND ci10.nb_catalog_item_order >= ci11.nb_catalog_item_order
                                                AND ci11.nb_catalog_item_next_sibling IS NOT NULL
                                                AND ci10.nb_catalog_item_order BETWEEN ci11.nb_catalog_item_order AND ci11.nb_catalog_item_next_sibling - 1
				                              GROUP BY ci11.nb_catalog_id, ci11.nb_catalog_item_level
                                              UNION ALL
				                             SELECT nb_catalog_id, max(nb_catalog_item_order) + 1
                                               FROM nb_catalog_item
				                              WHERE nb_catalog_id=%cat_id$d
                                              GROUP BY nb_catalog_id) AS t
                                              ORDER BY last_sibling ASC
                                              LIMIT 1) AS ci4,
                                    (SELECT nb_catalog_id, last_sibling
                                       FROM (SELECT ci11.nb_catalog_id, max(ci11.nb_catalog_item_next_sibling) last_sibling
						                       FROM nb_catalog_item ci10, nb_catalog_item ci11
			                                  WHERE ci10.nb_catalog_id=%cat_id$d
                                                AND ci10.nb_catalog_id=ci11.nb_catalog_id
                                                AND ci10.nb_catalog_item_id = %into_id$d
                                                AND ci10.nb_catalog_item_level >= ci11.nb_catalog_item_level
                                                AND ci10.nb_catalog_item_order >= ci11.nb_catalog_item_order
                                                AND ci11.nb_catalog_item_next_sibling IS NOT NULL
                                                AND ci10.nb_catalog_item_order BETWEEN ci11.nb_catalog_item_order AND ci11.nb_catalog_item_next_sibling - 1
	                                          GROUP BY ci11.nb_catalog_id, ci11.nb_catalog_item_level
                                              UNION ALL
		                                     SELECT nb_catalog_id, max(nb_catalog_item_order) + 1
                                               FROM nb_catalog_item
		                                      WHERE nb_catalog_id=%cat_id$d
                                              GROUP BY nb_catalog_id) AS t
	                                          ORDER BY last_sibling ASC
                                              LIMIT 1) AS ci5
                                SET ci3.nb_catalog_item_parent_id =
                                        if (ci3.nb_catalog_item_id = ci1.nb_catalog_item_id,
                                            ci2.nb_catalog_item_id,
                                            ci3.nb_catalog_item_parent_id
                                        ),
	                                ci3.nb_catalog_item_level =
                                        if (ci3.nb_catalog_item_order BETWEEN ci1.nb_catalog_item_order AND ci4.last_sibling - 1,
                                            ci2.nb_catalog_item_level + ci3.nb_catalog_item_level + 1 - ci1.nb_catalog_item_level,
                                            ci3.nb_catalog_item_level
                                        ),
                                    ci3.nb_catalog_item_order =
                                        if (ci3.nb_catalog_item_order BETWEEN ci1.nb_catalog_item_order AND ci4.last_sibling - 1,
                                            ci2.nb_catalog_item_order + ci3.nb_catalog_item_order + 1 - ci1.nb_catalog_item_order,
                                            if (ci3.nb_catalog_item_order BETWEEN ci5.last_sibling AND ci1.nb_catalog_item_order - 1,
                                                ci3.nb_catalog_item_order + ci4.last_sibling - ci1.nb_catalog_item_order,
                                                ci3.nb_catalog_item_order
                                            )
                                        ),
	                                ci3.nb_catalog_item_next_sibling =
                                        if (ci3.nb_catalog_item_id = ci1.nb_catalog_item_id,
                                            null,
                                            if (ci3.nb_catalog_item_id = ci2.nb_catalog_item_id,
                                                ci3.nb_catalog_item_order + 1 + ci4.last_sibling - ci1.nb_catalog_item_order,
                                                if (ci3.nb_catalog_item_next_sibling BETWEEN ci1.nb_catalog_item_order + 1 AND ci4.last_sibling - 1,
                                                    ci2.nb_catalog_item_order + ci3.nb_catalog_item_next_sibling + 1 - ci1.nb_catalog_item_order,
                                                    if (ci3.nb_catalog_item_next_sibling between ci5.last_sibling and ci1.nb_catalog_item_order,
                                                        ci3.nb_catalog_item_next_sibling + ci4.last_sibling - ci1.nb_catalog_item_order,
                                                        ci3.nb_catalog_item_next_sibling
                                                    )
                                                )
                                            )
                                        )
                          WHERE ci1.nb_catalog_item_id=%item_id$d
                            AND ci2.nb_catalog_item_id=%into_id$d
                            AND ci1.nb_catalog_id=ci2.nb_catalog_id
                            AND ci1.nb_catalog_id=ci3.nb_catalog_id
                            AND ci1.nb_catalog_id=ci4.nb_catalog_id
                            AND ci1.nb_catalog_id=ci5.nb_catalog_id',
                            array(
                                'cat_id' => $nb_catalog_id,
                                'item_id' => $nb_catalog_item_id,
                                'into_id' => $nb_catalog_into_id
                            )
                        );
                        $retval = true;
                    }
                } elseif (count($data) === 1) {
                    if ($nb_catalog_item_id !== $nb_catalog_into_id) {
                        $id = array_keys($data)[0];
                        throw new ENabuCatalogException(
                            ENabuCatalogException::ERROR_ITEM_NOT_INCLUDED_IN_CATALOG,
                            array($id === $nb_catalog_item_id ? $nb_catalog_into_id : $nb_catalog_item_id)
                        );
                    }
                } else {
                    throw new ENabuCatalogException(
                        ENabuCatalogException::ERROR_ITEM_NOT_INCLUDED_IN_CATALOG,
                        array(implode(', ', array_keys($data)))
                    );
                }
            } else {
                throw new ENabuCoreException(
                    ENabuCoreException::ERROR_UNEXPECTED_PARAM_VALUE,
                    array(print_r($nb_into_item, true), '$nb_into_item')
                );
            }
        } else {
            throw new ENabuCoreException(
                ENabuCoreException::ERROR_UNEXPECTED_PARAM_VALUE,
                array(print_r($nb_catalog_item, true), '$nb_catalog_item')
            );
        }

        return $retval;
    }

    /**
     * Override method to treat an Item when is saved the first time (inserted).
     * @param bool $trace If true, traces database queries.
     * @return bool Returns true if the Item is saved.
     */
    public function save($trace = false)
    {
        $retval = false;

        if ($this->isNew() && !$this->contains('nb_catalog_item_order')) {
            $last_sibling_id = $this->db->getQueryAsSingleField(
                'nb_catalog_item_id',
                'SELECT nb_catalog_item_id
                   FROM nb_catalog_item
                  WHERE nb_catalog_id=%cat_id$d
                    AND nb_catalog_item_level=1
                    AND nb_catalog_item_next_sibling IS NULL',
                array(
                    'cat_id' => $this->getCatalogId()
                )
            );
            $next_order = $this->db->getQueryAsSingleField(
                'next_order',
                'SELECT ifnull(next_order, def) + 1 AS next_order
                   FROM (SELECT max(nb_catalog_item_order) AS next_order, def
                           FROM nb_catalog_item ci1
                          RIGHT OUTER JOIN (SELECT 0 AS def FROM dual) ci2
                             ON nb_catalog_id=%cat_id$d
                        ) AS t',
                array(
                    'cat_id' => $this->getCatalogId()
                )
            );
            $this->setOrder($next_order);
            if ($retval = parent::save($trace)) {
                if (is_numeric($last_sibling_id)) {
                    $this->db->executeUpdate(
                        'UPDATE nb_catalog_item ci
                            SET ci.nb_catalog_item_next_sibling = %next_sibling$d
                          WHERE ci.nb_catalog_item_id=%item_id$d',
                        array(
                            'item_id' => $last_sibling_id,
                            'next_sibling' => $next_order
                        ), true
                    );
                }
            }
        } else {
            $retval = parent::save($trace);
        }

        return $retval;
    }
}
