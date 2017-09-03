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
use nabu\core\exceptions\ENabuCoreException;
use nabu\data\catalog\base\CNabuCatalogItemBase;
use nabu\data\catalog\exceptions\ENabuCatalogException;
use nabu\data\exceptions\ENabuDataException;
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
                    'select nb_catalog_item_id, nb_catalog_item_order, nb_catalog_item_level, nb_catalog_item_next_sibling '
                    . 'from nb_catalog_item '
                   . 'where nb_catalog_item_id in (%item_id$d, %before_id$d) '
                     . 'and nb_catalog_id = %cat_id$d',
                    array(
                        'cat_id' => $nb_catalog_id,
                        'item_id' => $nb_catalog_item_id,
                        'before_id' => $nb_catalog_before_id
                    ), true
                );
                if (count($data) === 2) {
                    if ($data[$nb_catalog_item_id]['nb_catalog_item_next_sibling'] !== $data[$nb_catalog_before_id]['nb_catalog_item_order']) {
                        if ($data[$nb_catalog_item_id]['nb_catalog_item_order'] < $data[$nb_catalog_before_id]['nb_catalog_item_order']) {
/*
select ci1.nb_catalog_item_id as i_id, ci1.nb_catalog_item_level as i_level, ci1.nb_catalog_item_order as i_order, ci1.nb_catalog_item_next_sibling as i_sibling,
       ci2.nb_catalog_item_id as b_id, ci2.nb_catalog_item_level as b_level, ci2.nb_catalog_item_order as b_order, ci2.nb_catalog_item_next_sibling as b_sibling,
       ci4.nb_catalog_item_id as p_id, ci4.nb_catalog_item_level as p_level, ci4.nb_catalog_item_order as p_order, ci4.nb_catalog_item_next_sibling as p_sibling,
       ci3.nb_catalog_item_id as n_id, ci3.nb_catalog_item_level as n_level, ci3.nb_catalog_item_order as n_order, ci3.nb_catalog_item_next_sibling as n_sibling,
       ci3.nb_catalog_item_id as n_id,
	   if (ci3.nb_catalog_item_order between ci1.nb_catalog_item_order and ci1.nb_catalog_item_next_sibling - 1,
		   ci3.nb_catalog_item_level + ci2.nb_catalog_item_level - ci1.nb_catalog_item_level,
           ci3.nb_catalog_item_level
		  ) as t_level,
       if (ci3.nb_catalog_item_order between ci1.nb_catalog_item_order and ci1.nb_catalog_item_next_sibling - 1,
           ci2.nb_catalog_item_order + ci3.nb_catalog_item_order - ci1.nb_catalog_item_next_sibling,
		   if (ci3.nb_catalog_item_order between ci1.nb_catalog_item_next_sibling and ci2.nb_catalog_item_order - 1,
			   ci3.nb_catalog_item_order + ci1.nb_catalog_item_order - ci1.nb_catalog_item_next_sibling,
               ci3.nb_catalog_item_order
			  )
		  ) as t_order,
	   if (ci3.nb_catalog_item_id = ci1.nb_catalog_item_id,
           ci2.nb_catalog_item_order,
           if (ci3.nb_catalog_item_id = ci4.nb_catalog_item_id,
			   ci2.nb_catalog_item_order + ci1.nb_catalog_item_order - ci1.nb_catalog_item_next_sibling,
  	           if (ci3.nb_catalog_item_next_sibling between ci1.nb_catalog_item_order + 1 and ci1.nb_catalog_item_next_sibling - 1,
		           ci2.nb_catalog_item_order + ci3.nb_catalog_item_next_sibling - ci1.nb_catalog_item_next_sibling,
                   if (ci3.nb_catalog_item_next_sibling between ci1.nb_catalog_item_next_sibling and ci2.nb_catalog_item_order - 1,
			           ci3.nb_catalog_item_next_sibling + ci1.nb_catalog_item_order - ci1.nb_catalog_item_next_sibling,
                       ci3.nb_catalog_item_next_sibling
					  )
			      )
		      )
		  )
  from nb_catalog_item as ci1, nb_catalog_item as ci2, nb_catalog_item as ci3, nb_catalog_item as ci4
 where ci1.nb_catalog_item_id=2
   and ci1.nb_catalog_id=ci2.nb_catalog_id
   and ci2.nb_catalog_item_id=5
   and ci1.nb_catalog_id=ci3.nb_catalog_id
   and ci1.nb_catalog_id=ci4.nb_catalog_id
   and ci4.nb_catalog_item_next_sibling=ci2.nb_catalog_item_order
order by ci3.nb_catalog_item_order
;
*/
                            $db->executeUpdate(
                                'update nb_catalog_item as ci1, nb_catalog_item as ci2, nb_catalog_item as ci3, nb_catalog_item as ci4 '
                                 . 'set ci3.nb_catalog_item_level = '
                                         . 'if (ci3.nb_catalog_item_order between ci1.nb_catalog_item_order and ci1.nb_catalog_item_next_sibling - 1, '
        		                             . 'ci3.nb_catalog_item_level + ci2.nb_catalog_item_level - ci1.nb_catalog_item_level, '
                                             . 'ci3.nb_catalog_item_level'
        		                            . '), '
                                     . 'ci3.nb_catalog_item_order = '
                                         . 'if (ci3.nb_catalog_item_order between ci1.nb_catalog_item_order and ci1.nb_catalog_item_next_sibling - 1, '
                                             . 'ci2.nb_catalog_item_order + ci3.nb_catalog_item_order - ci1.nb_catalog_item_next_sibling, '
    		                                 . 'if (ci3.nb_catalog_item_order between ci1.nb_catalog_item_next_sibling and ci2.nb_catalog_item_order - 1, '
    			                                 . 'ci3.nb_catalog_item_order + ci1.nb_catalog_item_order - ci1.nb_catalog_item_next_sibling, '
                                                 . 'ci3.nb_catalog_item_order'
    			                                . ')'
    		                                . '), '
    	                             . 'ci3.nb_catalog_item_next_sibling = '
                                         . 'if (ci3.nb_catalog_item_id = ci1.nb_catalog_item_id, '
                                             . 'ci2.nb_catalog_item_order, '
                                             . 'if (ci3.nb_catalog_item_id = ci4.nb_catalog_item_id, '
    			                                 . 'ci2.nb_catalog_item_order + ci1.nb_catalog_item_order - ci1.nb_catalog_item_next_sibling, '
      	                                         . 'if (ci3.nb_catalog_item_next_sibling between ci1.nb_catalog_item_order + 1 and ci1.nb_catalog_item_next_sibling - 1, '
    		                                         . 'ci2.nb_catalog_item_order + ci3.nb_catalog_item_next_sibling - ci1.nb_catalog_item_next_sibling, '
                                                     . 'if (ci3.nb_catalog_item_next_sibling between ci1.nb_catalog_item_next_sibling and ci2.nb_catalog_item_order - 1, '
    			                                         . 'ci3.nb_catalog_item_next_sibling + ci1.nb_catalog_item_order - ci1.nb_catalog_item_next_sibling, '
                                                         . 'ci3.nb_catalog_item_next_sibling'
    					                                . ')'
    			                                     .')'
    		                                    . ')'
    		                                . ') '
                               . 'where ci1.nb_catalog_item_id=%item_id$d '
                                 . 'and ci2.nb_catalog_item_id=%before_id$d '
                                 . 'and ci1.nb_catalog_id=ci2.nb_catalog_id '
                                 . 'and ci1.nb_catalog_id=ci3.nb_catalog_id '
                                 . 'and ci1.nb_catalog_id=ci4.nb_catalog_id '
                                 . 'and ci4.nb_catalog_item_next_sibling=ci2.nb_catalog_item_order',
                                array(
                                    'item_id' => $nb_catalog_item_id,
                                    'before_id' => $nb_catalog_before_id
                                ), true
                            );
                            $retval = true;
                        } elseif ($data[$nb_catalog_item_id]['nb_catalog_item_order'] < $data[$nb_catalog_before_id]['nb_catalog_item_order']) {
/*
select ci1.nb_catalog_item_id as i_id, ci1.nb_catalog_item_level as i_level, ci1.nb_catalog_item_order as i_order, ci1.nb_catalog_item_next_sibling as i_sibling,
       ci2.nb_catalog_item_id as b_id, ci2.nb_catalog_item_level as b_level, ci2.nb_catalog_item_order as b_order, ci2.nb_catalog_item_next_sibling as b_sibling,
       ci4.nb_catalog_item_id as p_id, ci4.nb_catalog_item_level as p_level, ci4.nb_catalog_item_order as p_order, ci4.nb_catalog_item_next_sibling as p_sibling,
       ci3.nb_catalog_item_id as n_id, ci3.nb_catalog_item_level as n_level, ci3.nb_catalog_item_order as n_order, ci3.nb_catalog_item_next_sibling as n_sibling,
       ci5.last_sibling,
       ci3.nb_catalog_item_id as n_id,
	   if (ci3.nb_catalog_item_order between ci1.nb_catalog_item_order and ifnull(ci1.nb_catalog_item_next_sibling, ci5.last_sibling) - 1,
		   ci3.nb_catalog_item_level + ci2.nb_catalog_item_level - ci1.nb_catalog_item_level,
           ci3.nb_catalog_item_level
		  ) as t_level,
       if (ci3.nb_catalog_item_order between ci1.nb_catalog_item_order and ifnull(ci1.nb_catalog_item_next_sibling, ci5.last_sibling) - 1,
           ci2.nb_catalog_item_order + ci3.nb_catalog_item_order - ci1.nb_catalog_item_order,
		   if (ci3.nb_catalog_item_order between ci2.nb_catalog_item_order and ci1.nb_catalog_item_order - 1,
			   ci3.nb_catalog_item_order + ifnull(ci1.nb_catalog_item_next_sibling, ci5.last_sibling) - ci1.nb_catalog_item_order,
               ci3.nb_catalog_item_order
			  )
		  ) as t_order,
	   if (ci3.nb_catalog_item_id = ci1.nb_catalog_item_id,
           ci2.nb_catalog_item_order + ifnull(ci1.nb_catalog_item_next_sibling, ci5.last_sibling) - ci1.nb_catalog_item_order,
           if (ci3.nb_catalog_item_id = ci4.nb_catalog_item_id,
			   ci1.nb_catalog_item_next_sibling,
			   if (ci3.nb_catalog_item_next_sibling between ci1.nb_catalog_item_order and ifnull(ci1.nb_catalog_item_next_sibling, ci5.last_sibling) - 1,
				   ci2.nb_catalog_item_order + ci3.nb_catalog_item_order - ci1.nb_catalog_item_order,
				   if (ci3.nb_catalog_item_next_sibling > ci2.nb_catalog_item_order,
					   ci3.nb_catalog_item_next_sibling + ifnull(ci1.nb_catalog_item_next_sibling, ci5.last_sibling) - ci1.nb_catalog_item_order,
					   ci3.nb_catalog_item_next_sibling
					  )
				  )
			  )
		  ) as t_sibling



  from nb_catalog_item as ci1, nb_catalog_item as ci2, nb_catalog_item as ci3, nb_catalog_item as ci4,
	   (select nb_catalog_id, last_sibling
          from (select ci11.nb_catalog_id, max(ci11.nb_catalog_item_next_sibling) last_sibling
                  from nb_catalog_item ci10, nb_catalog_item ci11
                 where ci10.nb_catalog_id=1
                   and ci10.nb_catalog_id=ci11.nb_catalog_id
                   and ci10.nb_catalog_item_id = 5
                   and ci10.nb_catalog_item_level >= ci11.nb_catalog_item_level
                   and ci10.nb_catalog_item_order >= ci11.nb_catalog_item_order
                   and ci11.nb_catalog_item_next_sibling is not null
                   and ci10.nb_catalog_item_order between ci11.nb_catalog_item_order and ifnull(ci11.nb_catalog_item_next_sibling, ci10.nb_catalog_item_order + 1) - 1
                 group by ci11.nb_catalog_id, ci11.nb_catalog_item_level
                 union all
                select nb_catalog_id, max(nb_catalog_item_order) + 1
                  from nb_catalog_item
				 where nb_catalog_id=1
                 group by nb_catalog_id) as t
                 order by last_sibling asc
                 limit 1) as ci5
 where ci1.nb_catalog_item_id=4
   and ci1.nb_catalog_id=ci2.nb_catalog_id
   and ci2.nb_catalog_item_id=2
   and ci1.nb_catalog_id=ci3.nb_catalog_id
   and ci1.nb_catalog_id=ci4.nb_catalog_id
   and ci4.nb_catalog_item_next_sibling=ci1.nb_catalog_item_order
   and ci1.nb_catalog_id=ci5.nb_catalog_id

 order by ci3.nb_catalog_item_order
;
*/
                            $retval = true;
                        }
                    }
                } elseif (count($data) === 1) {
                    $id = array_keys($data)[0];
                    throw new ENabuDataException(
                        ENabuDataException::ERROR_ITEM_NOT_INCLUDED_IN_CATALOG,
                        $id === $nb_catalog_item_id ? $nb_catalog_before_id : $nb_catalog_item_id
                    );
                } else {
                    throw new ENabuDataException(
                        ENabuDataException::ERROR_ITEM_NOT_INCLUDED_IN_CATALOG,
                        implode(', ', $array_keys($data))
                    );
                }
            } else {
                throw new ENabuCoreException(
                    ENabuCoreException::ERROR_UNEXPECTED_PARAM_VALUE,
                    array(print_r($nb_catalog_item, true), '$nb_before_item')
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
}
