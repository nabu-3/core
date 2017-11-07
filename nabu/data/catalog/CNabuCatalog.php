<?php

/*  Copyright 2009-2011 Rafael Gutierrez Martinez
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
use nabu\data\catalog\base\CNabuCatalogBase;
use nabu\data\exceptions\ENabuDataException;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.0 Surface
 * @version 3.0.12 Surface
 * @package \nabu\data\catalog
 */
class CNabuCatalog extends CNabuCatalogBase
{
    /**
     * Taxonomy list
     * @var CNabuCatalogTaxonomyList
     */
    private $nb_catalog_taxonomy_list;
    /**
     * Items list
     * @var CNabuCatalogItemList
     */
    private $nb_catalog_item_tree;
    /**
     * Tags list
     * @var CNabuCatalogTagList
     */
    private $nb_catalog_tag_list;

    /**
     * Overrides the constructor to instantiate lists of Items and Tags.
     * @param mixed $nb_catalog An intance of CNabuDataObject containing a field named nb_catalog_id or an ID.
     */
    public function __construct($nb_catalog = false)
    {
        parent::__construct($nb_catalog);

        $this->nb_catalog_taxonomy_list = new CNabuCatalogTaxonomyList($this);
        $this->nb_catalog_item_tree = new CNabuCatalogItemTree($this);
        $this->nb_catalog_tag_list = new CNabuCatalogTagList($this);
    }

    /**
     * Populates the catalog with all Items.
     * @param int $deep If 0 all items are retrieved, else if great of 0, then retrieves only the first $deep levels.
     */
    public function populate(int $deep = 0)
    {
        $this->nb_catalog_item_tree->populate($deep);
    }

    /**
     * Gets an Item from the catalog. Items needs to be in the root list.
     * @param mixed $key Scalar value of the indentifier used to search an item. Their type depends on $index value.
     * @param string $index Secondary index used to search or false to use primary key.
     * @return false|CNabuCatalogItem Returns an Item if $key matches, or false if not.
     */
    public function getItem($key, $index = false)
    {
        return $this->nb_catalog_item_tree->getItem($key, $index);
    }

    /**
     * Gets the available list of Items. if the list is empty or $force is true then forces to complete the list
     * from the storage.
     * @param bool $force If true, the list is updated from the storage.
     * @return CNabuCatalogItemList Returns the list of available Items.
     */
    public function getItems($force = false)
    {
        if ($this->nb_catalog_item_tree->isEmpty() || $force) {
            $this->nb_catalog_item_tree->populate();
        }

        return $this->nb_catalog_item_tree;
    }

    /**
     * Search a set of Items where the root items are in $roots arrays. If $nested is true, then search in the entire
     * branch of each root.
     * @param array|null $roots Roots ID collection.
     * @param string|null $q If setted, then the query uses the fulltext index to filter items.
     * @param mixed $nb_language The language used to perform searches. If null, then all available languages are used.
     * @param int $nested If true, search in the entire branch of each root.
     * @param bool $query_expansion If true, the fulltext search uses query expansion feature.
     * @return CNabuCatalogItemList Returns the list of Items in the Id collection with their respective branches to
     * reach all located Items.
     */
    public function searchItems(
        array $roots = null,
        string $q = null,
        $nb_language = null,
        int $nested = 0,
        $query_expansion = false
    ) {
        if (is_array($roots) && count($roots) > 0) {
            $use_roots = implode(', ', $roots);
            if (!preg_match('/^[[:digit:]]+(\s*,\s*[[:digit:]]+)*$/', $use_roots)) {
                throw new ENabuCoreException(
                    ENabuCoreException::ERROR_UNEXPECTED_PARAM_VALUE,
                    array('$roots', print_r($roots, true))
                );
            }
            if (is_numeric($nested) && $nested >= 0) {
                $query = 'select ci2.* '
                         . 'from nb_catalog_item ci1, nb_catalog_item ci2, nb_catalog_item_lang cil2 '
                        . 'where ci1.nb_catalog_id=%catalog_id$d '
                          . "and ci1.nb_catalog_item_id in ($use_roots) "
                          . 'and ci2.nb_catalog_id=ci1.nb_catalog_id '
                          . 'and ci2.nb_catalog_item_id=cil2.nb_catalog_item_id '
                          . 'and ((ci1.nb_catalog_item_next_sibling is null and ci2.nb_catalog_item_order > ci1.nb_catalog_item_order) or '
                               . '(ci2.nb_catalog_item_order between (ci1.nb_catalog_item_order + 1) and (ci1.nb_catalog_item_next_sibling - 1)))'
                          . ($nested > 0 ? "and ci2.nb_catalog_item_level-ci1.nb_catalog_item_level between 1 and $nested " : '')
                ;
            } else {
                $query = 'select ci2.* '
                         . 'from nb_catalog_item ci2, nb_catalog_item_lang cil2 '
                        . 'where ci2.nb_catalog_id=%catalog_id$d '
                          . "and ci2.nb_catalog_item_parent_id in ($use_roots) "
                          . 'and ci2.nb_catalog_item_id=cil2.nb_catalog_item_id '
                ;
            }
            $params = array(
                'catalog_id' => $this->getId()
            );
        } else {
            if (is_numeric($nested) && $nested >= 0) {
                $query = 'select ci2.* '
                         . 'from nb_catalog_item ci1, nb_catalog_item ci2, nb_catalog_item_lang cil2 '
                        . 'where ci2.nb_catalog_id=%catalog_id$d '
                          . 'and ci2.nb_catalog_item_id=cil2.nb_catalog_item_id '
                          . ($nested > 0 ? "and ci2.nb_catalog_item_level <= $nested " : '')
                ;
            } else {
                $query = 'select ci2.* '
                         . 'from nb_catalog_item ci2, nb_catalog_item_lang cil2 '
                        . 'where ci2.nb_catalog_id=%catalog_id$d '
                          . 'and ci2.nb_catalog_item_parent_id is null '
                          . 'and ci2.nb_catalog_item_id=cil2.nb_catalog_item_id '
                ;
            }
            $params = array(
                'catalog_id' => $this->getId()
            );
        }

        $nb_language_id = nb_getMixedValue($nb_language, 'nb_language_id');
        if (is_numeric($nb_language_id)) {
            $query .= 'and cil2.nb_language_id=%lang_id$d ';
            $params['lang_id'] = $nb_language_id;
        }

        if (is_string($q) && strlen($q) > 0) {
            $query .= 'and match(cil2.nb_catalog_item_lang_title, cil2.nb_catalog_item_lang_subtitle, '
                              . 'cil2.nb_catalog_item_lang_opening, cil2.nb_catalog_item_lang_content, '
                              . 'cil2.nb_catalog_item_lang_footer, cil2.nb_catalog_item_lang_aside) '
                      . 'against(\'%text$s\' in natural language mode'
                                 . ($query_expansion ? ' with query expansion' : '') .')'
            ;
            $params['text'] = $q;
        }

        $query .= 'order by ci2.nb_catalog_item_order';

        $nb_catalog_item_tree = CNabuCatalogItem::buildObjectListFromSQL(
            'nb_catalog_item_id',
            $query,
            $params,
            $this
        );

        return $nb_catalog_item_tree;
    }

    /**
     * Search a set of Items where the root items are in $roots arrays. If $nested is true, then search in the entire
     * branch of each root.
     * @param string $slug The slug to find.
     * @param array|null $roots Roots ID collection.
     * @param mixed $nb_language The language used to perform searches. If null, then all available languages are used.
     * @param int $deep If $deep = 0 then the search is performed in the entire branch or tree, else if $deep > 0
     * then the search is applied only to the number of sublevels contained in $deep.
     * E.g. $deep = 1 looks on for primary children, $deep = 2 looks for childrens and grandchildrens.
     * @return CNabuCatalogItem Returns the list of Items in the Id collection with their respective branches to
     * reach all located Items.
     */
    public function findItemBySlug(string $slug, array $roots = null, $nb_language = null, $deep = 0)
    {
        if (is_array($roots) && count($roots) > 0) {
            $use_roots = implode(', ', $roots);
            if (!preg_match('/^[[:digit:]]+(\s*,\s*[[:digit:]]+)*$/', $use_roots)) {
                throw new ENabuCoreException(
                    ENabuCoreException::ERROR_UNEXPECTED_PARAM_VALUE,
                    array('$roots', print_r($roots, true))
                );
            }
            $query = 'select ci2.* '
                     . 'from nb_catalog_item ci1, nb_catalog_item ci2, nb_catalog_item_lang cil2 '
                    . 'where ci1.nb_catalog_id=%catalog_id$d '
                      . "and ci1.nb_catalog_item_id in ($use_roots) "
                      . 'and ci2.nb_catalog_id=ci1.nb_catalog_id '
                      . 'and ci2.nb_catalog_item_id=cil2.nb_catalog_item_id '
                      . 'and ((ci1.nb_catalog_item_next_sibling is null and ci2.nb_catalog_item_order > ci1.nb_catalog_item_order) or '
                           . '(ci2.nb_catalog_item_order between (ci1.nb_catalog_item_order + 1) and (ci1.nb_catalog_item_next_sibling - 1)))'
            ;
            $params = array(
                'catalog_id' => $this->getId()
            );
            if ($deep > 0) {
                $query .= 'and ci2.nb_catalog_item_level between (ci1.nb_catalog_item_level + 1) and (ci1.nb_catalog_item_level + %deep$d) ';
                $params['deep'] = $deep;
            }
        } else {
            $query = 'select ci2.* '
                     . 'from nb_catalog_item ci2, nb_catalog_item_lang cil2 '
                    . 'where ci2.nb_catalog_id=%catalog_id$d '
                      . 'and ci2.nb_catalog_item_id=cil2.nb_catalog_item_id '
            ;
            $params = array(
                'catalog_id' => $this->getId()
            );
            if ($deep > 0) {
                $query .= 'and ci2.nb_catalog_item_level between 1 and %deep$d ';
                $params['deep'] = $deep;
            }
        }

        $nb_language_id = nb_getMixedValue($nb_language, 'nb_language_id');
        if (is_numeric($nb_language_id)) {
            $query .= 'and cil2.nb_language_id=%lang_id$d ';
            $params['lang_id'] = $nb_language_id;
        }

        $query .= 'and cil2.nb_catalog_item_lang_slug=\'%slug$s\'';
        $params['slug'] = $slug;

        if (is_object($retval = CNabuCatalogItem::buildObjectFromSQL($query, $params))) {
            $retval->setCatalog($this);
        }

        return $retval;
    }

    /**
     * Gets all taxonomy instances of the Catalog.
     * @param bool $force If true, then reloads the list from the storage.
     * @return CNabuCatalogTaxonomyList Returns the list of taxonomy instances.
     */
    public function getTaxonomies(bool $force = false)
    {
        if ($this->nb_catalog_taxonomy_list->isEmpty() || $force) {
            $this->nb_catalog_taxonomy_list->clear();
            $this->nb_catalog_taxonomy_list->merge(
                CNabuCatalogTaxonomy::getAllCatalogTaxonomies($this)
            );
        }

        return $this->nb_catalog_taxonomy_list;
    }

    /**
     * Gets the taxonomies keys as an array.
     * @return array Returns the array with all keys as index and the ID of each taxonomy as value.
     */
    public function getTaxonomyKeysIndex()
    {
        return $this->nb_catalog_taxonomy_list->getKeys(CNabuCatalogTaxonomyList::INDEX_KEY);
    }

    /**
     * Gets all tag instances of the Catalog.
     * @param bool $force If true, then reloads the list from the storage.
     * @return CNabuCatalogTagList Returns the list of tag instances.
     */
    public function getTags(bool $force = false)
    {
        if ($this->nb_catalog_tag_list->isEmpty() || $force) {
            $this->nb_catalog_tag_list->clear();
            $this->nb_catalog_tag_list->merge(
                CNabuCatalogTag::getAllCatalogTags($this)
            );
        }

        return $this->nb_catalog_tag_list;
    }

    /**
     * Overrides getTreeData method to add items and tag branches.
     * If $nb_language have a valid value, also adds a translation object
     * with current translation pointed by it.
     * @param int|object $nb_language Instance or Id of the language to be used.
     * @param bool $dataonly Render only field values and ommit class control flags.
     * @return array Returns a multilevel associative array with all data.
     */
    public function getTreeData($nb_language = null, $dataonly = false)
    {
        $trdata = parent::getTreeData($nb_language, $dataonly);

        $trdata['languages'] = $this->getLanguages();
        $trdata['taxonomies'] = $this->nb_catalog_taxonomy_list;
        $trdata['taxonomy_keys'] = $this->getTaxonomyKeysIndex();
        $trdata['tags'] = $this->nb_catalog_tag_list;
        $trdata['items'] = $this->nb_catalog_item_tree;

        return $trdata;
    }

    /**
     * Overrides refresh method to allow catalog subentities to be refreshed.
     * @param bool $force Forces to reload entities from the database storage.
     * @param bool $cascade Forces to reload child entities from the database storage.
     * @return bool Returns true if transations are empty or refreshed.
     */
    public function refresh(bool $force = false, bool $cascade = false) : bool
    {
        return parent::refresh($force, $cascade) &&
               (!$cascade || (
                   $this->getTaxonomies($force) &&
                   $this->getTags($force) &&
                   $this->getItems($force)
               ))
        ;
    }

    /**
     * Move a Catalog Item to put it in front of a different Item.
     * @param mixed $nb_catalog_item An Id or a CNabuDataObject instance containing a field named nb_catalog_item_id
     * to be moved.
     * @param mixed $nb_before_item An Id or a CNabuDataObject instance containing a field named nb_catalog_item_id
     * representing the Item where $nb_catalog_item will be putted in front of.
     * @return bool Returns true if the Item was moved or false if not.
     * @throws ENabuCoreException Raises an exception if some of requested Items does not corresponding to this Catalog.
     */
    public function moveItemBefore($nb_catalog_item, $nb_before_item)
    {
        return CNabuCatalogItem::moveItemBeforeItem($this, $nb_catalog_item, $nb_before_item);
    }
}
