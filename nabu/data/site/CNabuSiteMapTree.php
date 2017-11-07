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

namespace nabu\data\site;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\site
 */
class CNabuSiteMapTree extends CNabuSiteMapList
{
    public function __construct(CNabuSite $nb_site = null)
    {
        parent::__construct($nb_site);
    }

    public function populate(int $deep = 0)
    {
        parent::populate($deep);
        $this->buildTree();
    }

    public function buildTree()
    {
        $tree = array();
        $nb_owner = $this->getOwner();

        $this->iterate(
            function ($key, $item) use (&$tree, $nb_owner)
            {
                $item_parent_id = $item->getParentId();
                if (($item_parent_id === null && $nb_owner === null) ||
                    ($nb_owner !== null && $item_parent_id === $nb_owner->getId())
                ) {
                    $tree[$key] = $item;
                } else {
                    $parent = $this->getItem($item->getParentId());
                    if ($parent !== null) {
                        $parent->setChild($item);
                    }
                }

                return true;
            }
        );

        $this->clear();
        $this->mergeArray($tree);
    }
}
