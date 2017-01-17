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

namespace nabu\data\traits;
use nabu\data\CNabuDataObject;
use nabu\data\CNabuDataObjectList;
use nabu\data\interfaces\INabuDataObjectTreeNode;

/**
 * This trait implements a set of minimum functionalities to manage Tree Nodes.
 * You can apply this trait to your own classes to speed up your development,
 * or create your own management.
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\interfaces
 */
trait TNabuDataObjectTreeNode
{
    /**
     * List of child nodes connected to this node.
     * @var CNabuDataObjectList
     */
    private $nb_tree_child_list;
    /**
     * Parent instancce of this node or null if node is root.
     * @var INabuDataObjectTreeNode
     */
    private $nb_tree_parent;

    /**
     * Constructor fragment to initialize this trait when is used in a class
     */
    public function __treeNodeConstructor()
    {
        $this->nb_tree_child_list = $this->createChildContainer();
    }

    /**
     * Gets the list of childs as a CNabuDataObjectList instance.
     * @return CNabuDataObjectList List of childs.
     */
    public function getChilds()
    {
        return $this->nb_tree_child_list;
    }

    /**
     * Add a child if not exists. If child is in a deep level then looks for their parent level to connect both
     * instances.
     * @param CNabuDataObject $child The child to be added.
     * @return CNabuDataObject If child is added then returns the added instance else returns null.
     */
    public function addChild(INabuDataObjectTreeNode $child)
    {
        $retval = $this->nb_tree_child_list->addItem($child);
        $child->setParent($this);

        return $retval;
    }

    /**
     * Add or replaces a child. If child is in a deep level then looks for their parent level to connect both
     * instances.
     * @param CNabuDataObject $child The child to be added or replaced.
     * @return INabuDataObjectTreeNode Returns the parent instance where the child is added.
     */
    public function setChild(INabuDataObjectTreeNode $child)
    {
        $this->nb_tree_child_list->setItem($child);
        $child->setParent($this);

        return $this;
    }

    /**
     * Gets the parent instance of this tree node.
     * @return INabuDataObjectTreeNode Returns the tree parent or null if is a root node.
     */
    public function getParent()
    {
        return $this->nb_tree_parent;
    }

    /**
     * Sets the parent instance of this tree node.
     * @param INabuDataObjectTreeNode $parent The parent instance.
     * @return INabuDataObjectTreeNode Returns $this to grant chained setters.
     */
    public function setParent(INabuDataObjectTreeNode $parent)
    {
        $this->nb_tree_parent = $parent;

        if ($parent instanceof CNabuDataObject &&
            method_exists($parent, 'getId') &&
            $this instanceof CNabuDataObject &&
            method_exists($this, 'setParentId')
        ) {
            $this->setParentId($parent->getId());
        }

        return $this;
    }

    /**
     * Populates the Tree branch starting in the current node (this instance) from the storage.
     * @param int $level Level deep to get the branch inside the current node.
     */
    public function populate($level)
    {
        $this->nb_tree_child_list->populate($level);
    }

    public function __treeNodeGetTreeData(&$tdata, $nb_language = null, $dataonly = false)
    {
        $tdata['childs'] = $this->nb_tree_child_list;

        return $tdata;
    }
}
