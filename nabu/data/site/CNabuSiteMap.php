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

namespace nabu\data\site;
use nabu\core\CNabuEngine;
use nabu\data\interfaces\INabuDataObjectTreeNode;
use nabu\data\security\CNabuRole;
use nabu\data\security\interfaces\INabuRoleMask;
use nabu\data\site\base\CNabuSiteMapBase;
use nabu\data\site\builtin\CNabuBuiltInSiteMap;
use nabu\data\traits\TNabuDataObjectTreeNode;
use nabu\http\managers\CNabuHTTPSecurityManager;

/**
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\site
 */
class CNabuSiteMap extends CNabuSiteMapBase implements INabuDataObjectTreeNode, INabuRoleMask
{
    use TNabuDataObjectTreeNode;

    /**
     * Defines the Kind Separator value for a Site Map
     * @var string
     */
    const KIND_SEPARATOR = 'T';
    /**
     * Defines the Kind Item value for a Site Map
     * @var string
     */
    const KIND_ITEM = 'F';

    /**
     * Role List to be applied to this Site Map node.
     * @var CNabuSiteMapRoleList
     */
    private $nb_site_map_role_list = null;
    /**
     * Flag to know if this node is in the breadcrumb
     * @var bool
     */
    private $in_breadcrumb = false;
    /**
     * Flag to know if this node is the current node for current Site Target
     * @var bool
     */
    private $is_current = false;

    /**
     * Default constructor. If $nb_site_map is provided, then deserializes the instance from the storage.
     * @param mixed $nb_site_map A valid Id, GUID or object containing a field named nb_site_map_id.
     */
    public function __construct($nb_site_map = false)
    {
        parent::__construct($nb_site_map);
        $this->__treeNodeConstructor();

        $this->nb_site_map_role_list = new CNabuSiteMapRoleList($this);
    }

    /**
     * Check if URI uses a Site Target.
     * @return bool Returns true if uses a URI as a Site Target.
     */
    public function isUsingURIAsTarget()
    {
        return $this->getUseURI() === CNabuSiteTargetLink::USE_URI_TRANSLATED;
    }

    /**
     * Check if URI uses a URL.
     * @return bool Returns true if uses a URI as a URL.
     */
    public function isUsingURIAsURL()
    {
        return $this->getUseURI() === CNabuSiteTargetLink::USE_URI_URL;
    }

    /**
     * Check if URI have a valid value.
     * @return bool Returns true if URI have a valid value.
     */
    public function isURIUsed()
    {
        return $this->getUseURI() !== CNabuSiteTargetLink::USE_URI_NONE;
    }

    /**
     * Check if this Site Map is of kind Separator.
     * @return bool Returns true if is a Separator.
     */
    public function isSeparator()
    {
        return $this->getSeparator() === self::KIND_SEPARATOR;
    }

    public function isItem()
    {
        return $this->getSeparator() === self::KIND_ITEM;
    }

    public function isBreadcrumb()
    {
        return $this->in_breadcrumb;
    }

    /**
     * Checks if this Site Map node is the current node that points to current Site Target.
     * @return bool Returns true if this is the current node.
     */
    public function isCurrent()
    {
        return $this->is_current;
    }

    /**
     * Checks is this node is applicable for Work Customers.
     * @return bool Returns true if is applicable.
     */
    public function isWorkCustomerRequired()
    {
        return $this->hasValue('nb_site_map_customer_required') &&
               in_array($this->getValue('nb_site_map_customer_required'), array('B', 'T'));
    }

    /**
     * Checks is this node is applicable without Work Customers.
     * @return bool Returns true if is applicable.
     */
    public function isWorkCustomerNotRequired()
    {
        return $this->hasValue('nb_site_map_customer_required') &&
               in_array($this->getValue('nb_site_map_customer_required'), array('B', 'F'));
    }

    public function setSiteTarget(CNabuSiteTarget $nb_site_target, $field = NABU_SITE_TARGET_FIELD_ID)
    {
        $this->setUseURI(CNabuSiteTargetLink::USE_URI_TRANSLATED);
        return parent::setSiteTarget($nb_site_target, $field);

        return $this;
    }

    public function addSiteMapForTarget(CNabuSiteTarget $nb_site_target, $order, CNabuRole $nb_role = null)
    {
        if ($nb_role === null) {
            $nb_role = CNabuEngine::getEngine()->getHTTPServer()->getSite()->getDefaultRole();
        }

        $nb_site_map = ($this->isBuiltIn() ? new CNabuBuiltInSiteMap() : new CNabuSiteMap());
        $nb_site_map
            ->setSiteTarget($nb_site_target)
            ->setOrder($order)
        ;

        $this->addChild($nb_site_map);

        return $nb_site_map;
    }

    public function addSiteMapForURL($order, CNabuRole $nb_role = null)
    {
        if ($nb_role === null) {
            $nb_role = CNabuEngine::getEngine()->getHTTPServer()->getSite()->getDefaultRole();
        }

        $nb_site_map = ($this->isBuiltIn() ? new CNabuBuiltInSiteMap() : new CNabuSiteMap());
        $nb_site_map
            ->setUseURI(CNabuSiteTargetLink::USE_URI_URL)
            ->setOrder($order)
        ;

        $this->addChild($nb_site_map);

        return $nb_site_map;
    }

    /**
     * Add a Role to this Site Map node.
     * @param CNabuSiteMapRole $nb_role Role to be added.
     * @return CNabuSiteMapRole Returns the inserted Role instance.
     */
    public function addRole(CNabuSiteMapRole $nb_role)
    {
        if ($nb_role->isValueNumeric(NABU_ROLE_FIELD_ID) || $nb_role->isValueGUID(NABU_ROLE_FIELD_ID)) {
            $nb_role->setSiteMap($this);
            $this->nb_site_map_role_list->addItem($nb_role);
        }

        return $nb_role;
    }

    /**
     * Canonizes the Site Map node to be applied to a concrete target.
     * Updates in cascade all childs and create the final_url in each translation.
     * Mark the node as included (or not) in the breadcrumb.
     * @param CNabuSiteTarget $nb_site_target Site Target where the Site Map node is canonized.
     * @param array $match_values If the Site Target is a RegExp or Like URL, here is the list of values to fill it.
     */
    public function canonize(CNabuSiteTarget $nb_site_target, array $match_values = null)
    {
        $translations = $this->getTranslations();
        $translations->iterate(
            function ($key, $nb_site_map_translation)
            {
                if ($this->isUsingURIAsTarget()) {
                    if ($this->nb_site_target=== null &&
                        $this->nb_site !== null &&
                        $this->isValueNumeric('nb_site_target_id')
                    ) {
                        $aux_site_target = $this->nb_site->getTarget($this);
                        if ($aux_site_target instanceof CNabuSiteTarget) {
                            $this->setSiteTarget($this->nb_site->getTarget($this));
                        }
                    }
                    if ($this->nb_site_target !== null) {
                        $nb_site_map_translation->setFinalURL(
                            $this->nb_site_target->getFullyQualifiedURL($nb_site_map_translation)
                        );
                    }
                } elseif ($this->isUsingURIAsURL()) {
                    $nb_site_map_translation->setFinalURL($nb_site_map_translation->getURL());
                }

                return true;
            }
        );

        $this->in_breadcrumb = $this->is_current =
            ($this->isUsingURIAsTarget() && $nb_site_target->matchValue($this, 'nb_site_target_id'));

        $this->getChilds()->iterate(
            function ($key, $nb_site_map) use($nb_site_target, $match_values)
            {
                $nb_site_map->canonize($nb_site_target, $match_values);
                $this->in_breadcrumb = ($this->in_breadcrumb || $nb_site_map->isBreadcrumb());

                return true;
            }
        );
    }

    static public function getMapsForSite($nb_site)
    {
        $nb_site_id = nb_getMixedValue($nb_site, NABU_SITE_FIELD_ID);
        if (is_numeric($nb_site_id)) {
            $retval = CNabuSiteMap::buildObjectListFromSQL(
                'nb_site_map_id',
                'select * '
                . 'from nb_site_map '
               . 'where nb_site_id=%site_id$d '
                 //. 'and nb_site_map_parent_id is null '
               . 'order by nb_site_map_order',
                array(
                    'site_id' => $nb_site_id
                ),
                ($nb_site instanceof CNabuSite ? $nb_site : null)
            );
        } else {
            $retval = new CNabuSiteMapList();
        }

        return $retval;
    }

    public function applyRoleMask(CNabuRole $nb_role, array $additional = null)
    {
        $nb_role_id = $nb_role->getId();
        $nb_role_mask = $this->nb_site_map_role_list->getItem($nb_role_id);
        error_log("==== **** ==== " . print_r($nb_role_mask->getTreeData(null, true), true));
        error_log("==== **** ==== " . print_r($additional, true));
        $this->nb_site_map_role_list->clear();
        if ($nb_role_mask instanceof CNabuSiteMapRole) {
            if (is_array($additional) &&
                array_key_exists(CNabuHTTPSecurityManager::ROLE_MASK_USER_SIGNED, $additional) &&
                is_bool($additional[CNabuHTTPSecurityManager::ROLE_MASK_USER_SIGNED]) &&
                (!array_key_exists(CNabuHTTPSecurityManager::ROLE_MASK_WORK_CUSTOMER, $additional) ||
                 is_bool($additional[CNabuHTTPSecurityManager::ROLE_MASK_WORK_CUSTOMER])
                )
            ) {
                $user_signed = $additional[CNabuHTTPSecurityManager::ROLE_MASK_USER_SIGNED];
                $work_customer = array_key_exists(CNabuHTTPSecurityManager::ROLE_MASK_WORK_CUSTOMER, $additional) &&
                    $additional[CNabuHTTPSecurityManager::ROLE_MASK_WORK_CUSTOMER];
                if ((($user_signed && $nb_role_mask->isForPrivateZone()) ||
                    (!$user_signed && $nb_role_mask->isForPublicZone())) &&
                    (($work_customer && $this->isWorkCustomerRequired()) ||
                     (!$work_customer && $this->isWorkCustomerNotRequired()))
                ) {
                    $this->nb_site_map_role_list->addItem($nb_role_mask);
                    $this->nb_tree_child_list->applyRoleMask($nb_role, $additional);
                } else {
                    $this->nb_tree_child_list->clear();
                }
            }
        }

        return $this->nb_site_map_role_list->isFilled();
    }

    /* === Below, methods implemented to act as a INabuDataObjectTreeNode === */
    public function createChildContainer()
    {
        $nb_site_map_tree = new CNabuSiteMapTree($this->getSite());
        $nb_site_map_tree->setOwner($this);

        return $nb_site_map_tree;
    }

    public function getTreeData($nb_language = null, $dataonly = false)
    {
        $trdata = parent::getTreeData($nb_language, $dataonly);

        $trdata['breadcrumb'] = $this->in_breadcrumb;
        $trdata['current'] = $this->is_current;
        $trdata['roles'] = $this->nb_site_map_role_list;
        $this->__treeNodeGetTreeData($trdata, $nb_language, $dataonly);

        return $trdata;
    }
    /* === End of INabuDataObjectTreeNode methods implementation === */
}
