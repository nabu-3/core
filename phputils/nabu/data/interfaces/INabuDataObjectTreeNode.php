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

namespace nabu\data\interfaces;
use nabu\data\CNabuDataObject;
use nabu\data\CNabuDataObjectList;
use nabu\data\interfaces\INabuDataObjectTreeNode;

/**
 * Interface to define minimum access to Tree structures.
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\interfaces
 */
interface INabuDataObjectTreeNode
{
    /**
     * Creates the child list container.
     * @return CNabuDataObjectList Returns a list instance created to manage the childs list.
     */
    public function createChildContainer();
    /**
     * Gets the list of childs as a CNabuDataObjectList instance.
     * @return CNabuDataObjectList List of childs.
     */
    public function getChilds();
    /**
     * Add a child if not exists. If child is in a deep level then looks for their parent level to connect both
     * instances.
     * @param INabuDataObjectTreeNode $child The child to be added.
     * @return INabuDataObjectTreeNode If child is added then returns the added instance else returns null.
     */
    public function addChild(INabuDataObjectTreeNode $child);
    /**
     * Add or replaces a child. If child is in a deep level then looks for their parent level to connect both
     * instances.
     * @param INabuDataObjectTreeNode $child The child to be added or replaced.
     * @return INabuDataObjectTreeNode Returns the parent instance where the child is added.
     */
    public function setChild(INabuDataObjectTreeNode $child);
    /**
     * Gets the parent instance of this tree node.
     * @return INabuDataObjectTreeNode Returns the tree parent or null if is a root node.
     */
    public function getParent();
    /**
     * Sets the parent instance of this tree node.
     * @param INabuDataObjectTreeNode $parent The parent instance.
     * @return INabuDataObjectTreeNode Returns $this to grant chained setters.
     */
    public function setParent(INabuDataObjectTreeNode $parent);
}
