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

namespace nabu\data\site;
use nabu\core\exceptions\ENabuCoreException;
use nabu\data\CNabuDataObject;
use nabu\data\CNabuDataObjectList;
use nabu\data\CNabuDataObjectListIndex;

/**
 * Manages a secondary index in a list of CNabuSiteTargetList using their URLs.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\site
 */
class CNabuSiteTargetListIndexURL extends CNabuDataObjectListIndex
{
    /**
     * URL type to accept items in this index.
     * @var string
     */
    private $url_filter;

    /**
     * Overrides parent constructor to receive $url_filter parameter.
     * Creates the instance.
     * @param CNabuDataObjectList $list List parent of this index.
     * @param string $url_filter Type of URLs to be used in this index.
     * @param string $key_field Key field name to build the index.
     * @param string $key_order Field used to order the index.
     * @param string $name Name of the index.
     */
    public function __construct(CNabuDataObjectList $list, $url_filter, $key_field, $key_order = null, $name = null)
    {
        parent::__construct($list, $key_field, $key_order, $name);

        $this->url_filter = $url_filter;
    }
    /**
     * Extract Nodes list for translations in an item in this node.
     * @param CNabuDataObject $item Item of which will extract the nodes.
     * @return array Returns an array of found nodes or null when they are not available nodes.
     */
    protected function extractNodes(CNabuDataObject $item)
    {
        $retval = null;

        if ($item instanceof CNabuSiteTarget) {
            if ($item->getURLFilter() === $this->url_filter && $item->hasTranslations()) {
                $nodes = array();
                $main_index_name = $this->list->getIndexedFieldName();
                $item->getTranslations()->iterate(
                    function ($key, $translation) use (&$nodes, $main_index_name, $item) {
                        if (($translation->isValueNumeric($main_index_name) ||
                             $translation->isValueGUID($main_index_name)
                            ) &&
                            ($translation->isValueString($this->key_field) ||
                             $translation->isValueNumeric($this->key_field)
                            )
                        ) {
                            $key = $translation->getValue($this->key_field);
                            $nodes[$key] = array(
                                'key' => $key,
                                'pointer' => $translation->getValue($main_index_name)
                            );
                            if ($translation->isValueNumeric($this->order_field) ||
                                $translation->isValueString($this->order_field)
                            ) {
                                $nodes[$key]['order'] = $translation->getValue($this->order_field);
                            } elseif ($item->isValueNumeric($this->order_field) ||
                                $item->isValueString($this->order_field)
                            ) {
                                $nodes[$key]['order'] = $item->getValue($this->order_field);
                            }
                        }
                        return true;
                    }
                );
                if (count($nodes) > 0) {
                    $retval = $nodes;
                }
            }
        } else {
            throw new ENabuCoreException(
                ENabuCoreException::ERROR_UNEXPECTED_PARAM_CLASS_TYPE,
                array('$item', get_class($item))
            );
        }

        return $retval;
    }

}
