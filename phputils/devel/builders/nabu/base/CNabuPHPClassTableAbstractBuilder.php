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

namespace devel\builders\nabu\base;
use devel\builders\CNabuAbstractBuilder;
use devel\builders\php\CNabuPHPClassBuilder;
use nabu\core\exceptions\ENabuCoreException;
use nabu\db\interfaces\INabuDBDescriptor;

/**
 * This class implements a lot of methods shared between different child classes.
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \nabu\devel\builders\nabu
 */
class CNabuPHPClassTableAbstractBuilder extends CNabuPHPClassBuilder
{
    /**
     * Entity Name used for comment purposes.
     * @var string
     */
    protected $entity_name = null;
    /**
     * Class Namespace.
     * @var string
     */
    protected $class_namespace = null;
    /**
     * Name of the table.
     * @var string
     */
    protected $table_name = null;
    /**
     * Flag to determine if entity have translations.
     * @var bool
     */
    protected $is_translated = false;
    /**
     * Flag to determine if entity are translations.
     * @var bool
     */
    protected $is_translation = false;
    /**
     * Flag to determine if entity is a Customer child entity.
     * @var bool
     */
    protected $is_customer_child = false;
    /**
     * Flag to determine if entity is a Customer foreign entity.
     * @var bool
     */
    protected $is_customer_foreign = false;
    /**
     * Flag to determine if entity is a Site child entity.
     * @var bool
     */
    protected $is_site_child = false;
    /**
     * Flag to determine if entity s a Site foreign entity.
     * @var bool
     */
    protected $is_site_foreign = false;
    /**
     * Flag to determine if is a Site Target child entity.
     * @var bool
     */
    protected $is_site_target_child = false;
    /**
     * Flag to determine if is a Site Target foreign entity.
     * @var bool
     */
    protected $is_site_target_foreign = false;
    /**
     * Flag to determine if is a Commerce child entity.
     * @var bool
     */
    protected $is_commerce_child = false;
    /**
     * Flag to determine if is a Commerce foreign entity.
     * @var bool
     */
    protected $is_commerce_foreign = false;
    /**
     * Flag to determine if is a Catalog child entity.
     * @var bool
     */
    protected $is_catalog_child = false;
    /**
     * Flag to determine if is a Catalog foreign entity.
     * @var bool
     */
    protected $is_catalog_foreign = false;
    /**
     * Flag to determine if is a Medioteca child entity.
     * @var bool
     */
    protected $is_medioteca_child = false;
    /**
     * Flag to determine if is a Medioteca foreign entity.
     * @var bool
     */
    protected $is_medioteca_foreign = false;
    /**
     * Flag to determine if is a Role child entity.
     * @var bool
     */
    protected $is_role_child = false;
    /**
     * Flag to determine if is a Role foreign entity.
     * @var bool
     */
    protected $is_role_foreign = false;
    /**
     * Dictionary for table to entity conversion names.
     * @var array
     */
    protected $dictionary = false;
    /**
     * Table descriptor in array raw format.
     * @var INabuDBDescriptor
     */
    private $table_descriptor = null;

    /**
     * The constructor checks if all parameters have valid values, and throws an exception if not.
     * @param CNabuAbstractBuilder $container Container builder object
     * @param string $namespace Namespace of the class to be generated
     * @param string $name Class name to be generated without namespace
     * @param string $entity_name Entity name. This value is used for comment purposes
     * @param INabuDBDescriptor $table_descriptor Table descriptor instance.
     * @param bool $abstract Defines if the class is abstract or not
     * @throws ENabuCoreException Throws an exception if some parameter or the primary key are not valids.
     */
    public function __construct(
        CNabuAbstractBuilder $container,
        INabuDBDescriptor $table_descriptor,
        $namespace,
        $name,
        $entity_name,
        $abstract
    ) {
        parent::__construct($container, $name, $abstract);

        if (strlen($namespace) === 0) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_CONSTRUCTOR_PARAMETER_IS_EMPTY,
                    array('$entity_namespace')
            );
        }

        if (strlen($entity_name) === 0) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_CONSTRUCTOR_PARAMETER_IS_EMPTY,
                    array('$entity_name')
            );
        }

        $this->class_namespace = $namespace;
        $this->entity_name = $entity_name;
        $this->table_descriptor = $table_descriptor;

        if (!$this->checkTable()) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_CLASS_CANNOT_BE_BUILT, array($this->name));
        }

        $this->checkSecondaryConstraints();
    }

    public function dumpStatus($padding)
    {
        error_log("");
        error_log($padding . "Translated: " . ($this->is_translated ? 'YES' : 'NO'));
        error_log($padding . "Translation: " . ($this->is_translation ? 'YES' : 'NO'));
        error_log($padding . "Customer Child: " . ($this->is_customer_child ? 'YES' : 'NO'));
        error_log($padding . "Customer Foreign: " . ($this->is_customer_foreign ? 'YES' : 'NO'));
        error_log($padding . "Commerce Child: " . ($this->is_commerce_child ? 'YES' : 'NO'));
        error_log($padding . "Commerce Foreign: " . ($this->is_commerce_foreign ? 'YES' : 'NO'));
        error_log($padding . "Catalog Child: " . ($this->is_catalog_child ? 'YES' : 'NO'));
        error_log($padding . "Catalog Foreign: " . ($this->is_catalog_foreign ? 'YES' : 'NO'));
        error_log($padding . "Site Child: " . ($this->is_site_child ? 'YES' : 'NO'));
        error_log($padding . "Site Foreign: " . ($this->is_site_foreign ? 'YES' : 'NO'));
        error_log($padding . "Site Target Child: " . ($this->is_site_target_child ? 'YES' : 'NO'));
        error_log($padding . "Site Target Foreign: " . ($this->is_site_target_foreign ? 'YES' : 'NO'));
        error_log($padding . "Medioteca Child: " . ($this->is_medioteca_child ? 'YES' : 'NO'));
        error_log($padding . "Medioteca Foreign: " . ($this->is_medioteca_foreign ? 'YES' : 'NO'));
        error_log($padding . "Role Child: " . ($this->is_role_child ? 'YES' : 'NO'));
        error_log($padding . "Role Foreign: " . ($this->is_role_foreign ? 'YES' : 'NO'));
    }

    /**
     * Gets current dictionary
     * @return array Returns the dictionary if exists or null if none.
     */
    public function getDictionary()
    {
        return $his->dictionary;
    }

    /**
     * Stablishes the dictionary to be used to transform table names into entity or method names
     * @param array $dictionary
     */
    public function setDictionary($dictionary)
    {
        $this->dictionary = $dictionary;
    }

    /**
     * Get the Storage Descriptor of the table.
     * @return INabuDBDescriptor Returns the descriptor instance if exists or false if not.
     */
    public function getStorageDescriptor()
    {
        return $this->table_descriptor;
    }

    /**
     * Checks the table descriptor integrity and the validity of the primary key.
     * @return bool Returns true if the descriptor is valid.
     */
    public function checkTable()
    {
        return is_string($this->table_name = $this->table_descriptor->getStorageName()) &&
            $this->table_descriptor->hasFields() &&
            $this->checkPrimaryConstraint()
        ;
    }

    /**
     * Check the Primary Constraint to determine if class can be built.
     * @return bool Returns true if the Primary Key is valid.
     */
    public function checkPrimaryConstraint()
    {
        $retval = false;
        $this->is_translation = false;

        if (($retval = $this->table_descriptor->hasPrimaryConstraint())) {
            if ($this->table_descriptor->getPrimaryConstraintSize() > 1) {
                $this->is_translation =
                    $this->table_descriptor->hasPrimaryConstraintField(NABU_LANG_FIELD_ID, 2);
                $this->is_customer_child =
                    $this->checkPrimaryRelationship(NABU_CUSTOMER_TABLE, NABU_CUSTOMER_FIELD_ID);
                $this->is_commerce_child =
                    $this->checkPrimaryRelationship(NABU_COMMERCE_TABLE, NABU_COMMERCE_FIELD_ID);
                $this->is_catalog_child =
                    $this->checkPrimaryRelationship(NABU_CATALOG_TABLE, NABU_CATALOG_FIELD_ID);
                $this->is_site_child =
                    $this->checkPrimaryRelationship(NABU_SITE_TABLE, NABU_SITE_FIELD_ID);
                $this->is_site_target_child =
                    $this->checkPrimaryRelationship(NABU_SITE_TARGET_TABLE, NABU_SITE_TARGET_FIELD_ID);
                $this->is_medioteca_child =
                    $this->checkPrimaryRelationship(NABU_MEDIOTECA_TABLE, NABU_MEDIOTECA_FIELD_ID);
                $this->is_role_child =
                    $this->checkPrimaryRelationship(NABU_ROLE_TABLE, NABU_ROLE_FIELD_ID);
            }
        }

        return $retval;
    }

    /**
     * Check if a table is related with another by their Primary Constraint.
     * @param string $table Table name to match.
     * @param string $field Field in $table to match.
     * @param false|int $position If setted, then determines the position where the field would be present. By default in
     * first position. If false, any position is valid.
     * @return bool Returns true if both tables are connected.
     */
    protected function checkPrimaryRelationship($table, $field, $position = 1)
    {
        return $this->table_name !== $table &&
            nb_strStartsWith($this->table_name, $table . '_') &&
            $this->table_descriptor->hasPrimaryConstraintField($field, $position)
        ;
    }

    /**
     * Check if secondary constraints exists and they are related with main entities of Nabu.
     */
    public function checkSecondaryConstraints()
    {
        $this->is_customer_foreign = $this->checkSecondaryRelationship(NABU_CUSTOMER_TABLE, NABU_CUSTOMER_FIELD_ID);
        $this->is_commerce_foreign = $this->checkSecondaryRelationship(NABU_COMMERCE_TABLE, NABU_COMMERCE_FIELD_ID);
        $this->is_catalog_foreign = $this->checkSecondaryRelationship(NABU_CATALOG_TABLE, NABU_CATALOG_FIELD_ID);
        $this->is_site_foreign = $this->checkSecondaryRelationship(NABU_SITE_TABLE, NABU_SITE_FIELD_ID);
        $this->is_site_target_foreign = $this->checkSecondaryRelationship(
            NABU_SITE_TARGET_TABLE, NABU_SITE_TARGET_FIELD_ID
        );
        $this->is_medioteca_foreign = $this->checkSecondaryRelationship(NABU_MEDIOTECA_TABLE, NABU_MEDIOTECA_FIELD_ID);
        $this->is_role_foreign = $this->checkSecondaryRelationship(NABU_ROLE_TABLE, NABU_ROLE_FIELD_ID);
    }

    /**
     * Check if table have and index related with other table and associate it.
     * @param string $table Table name to match.
     * @param string $field Field in $table to match.
     * @return bool Returns true if both tables are connected..
     */
    public function checkSecondaryRelationship($table, $field)
    {
        return $this->table_descriptor->hasSecondaryConstraints() &&
            $this->table_descriptor->hasField($field) &&
            !$this->table_descriptor->hasPrimaryConstraintField($field) &&
            $this->table_descriptor->hasSecondaryConstraintWithFields(array($field))
        ;
    }
}