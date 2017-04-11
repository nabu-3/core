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

namespace nabu\data\customer;

use \nabu\data\customer\base\CNabuCustomerBase;
use nabu\core\exceptions\ENabuCoreException;
use nabu\data\CNabuDataObject;
use nabu\data\catalog\CNabuCatalog;
use nabu\data\catalog\CNabuCatalogList;
use nabu\data\commerce\CNabuCommerce;
use nabu\data\commerce\CNabuCommerceList;
use nabu\data\lang\CNabuLanguageList;
use nabu\data\medioteca\CNabuMedioteca;
use nabu\data\medioteca\CNabuMediotecaList;
use nabu\data\messaging\CNabuMessaging;
use nabu\data\messaging\CNabuMessagingList;
use nabu\data\project\CNabuProject;
use nabu\data\project\CNabuProjectList;
use nabu\data\site\CNabuSite;
use nabu\data\site\CNabuSiteList;

/**
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @since 3.0.0 Surface
 * @version 3.0.12 Surface
 * @package \nabu\data\customer
 */
class CNabuCustomer extends CNabuCustomerBase
{
    /** @var CNabuMediotecaList $nb_medioteca_list List of Mediotecas. This list can be filled only with requested
     * Mediotecas (on demand) or with a full list. */
    private $nb_medioteca_list;
    /** @var CNabuSiteList $nb_site_list List of Sites. This list can be filled only with requested Sites (on demand)
     * or with a full list. */
    private $nb_site_list;
    /** @var CNabuCommerceList $nb_commerce_list List of Commerces. This list can be filled only with requested
     * Commerces (on demand) or with a full list. */
    private $nb_commerce_list;
    /** @var CNabuCatalogList $nb_catalog_list List of Catalogs. This list can be filled only with requested
     * Catalogs (on demand) or with a full list. */
    private $nb_catalog_list;
    /** @var CNabuMessagingList $nb_messaging_list List of Messaging. This list can be filled only with requested
     * Messagings (on demand) or with a full list. */
    private $nb_messaging_list;
    /** @var CNabuProject $nb_project_list List of Projects. This list can be filled only with requested Projects
     * (on demand) or with a full list. */
    private $nb_project_list;

    /**
     * Creates the instance and initializes class variables.
     * @param mixed $nb_customer An instance inherited from CNabuDataObject that contains a field named nb_customer_id
     * or an ID that represents the Customer to be instantiated.
     */
    public function __construct($nb_customer = false)
    {
        parent::__construct($nb_customer);

        $this->nb_medioteca_list = new CNabuMediotecaList($this);
        $this->nb_site_list = new CNabuSiteList($this);
        $this->nb_commerce_list = new CNabuCommerceList($this);
        $this->nb_catalog_list = new CNabuCatalogList($this);
        $this->nb_messaging_list = new CNabuMessagingList($this);
        $this->nb_project_list = new CNabuProjectList($this);
    }

    public function relinkDB()
    {
        parent::relinkDB();

        if ($this->nb_medioteca_list !== null) {
            $this->nb_medioteca_list->iterate(function($key, $nb_medioteca) {
                $nb_medioteca->relinkDB();
                return true;
            });
        }

        if ($this->nb_site_list !== null) {
            $this->nb_site_list->iterate(function($key, $nb_site) {
                $nb_site->relinkDB();
                return true;
            });
        }

        if ($this->nb_commerce_list !== null) {
            $this->nb_commerce_list->iterate(function($key, $nb_commerce) {
                $nb_commerce->relinkDB();
                return true;
            });
        }

        if ($this->nb_catalog_list !== null) {
            $this->nb_catalog_list->iterate(function($key, $nb_catalog) {
                $nb_catalog->relinkDB();
                return true;
            });
        }

        if ($this->nb_messaging_list !== null) {
            $this->nb_messaging_list->iterate(function($key, $nb_messaging) {
                $nb_messaging->relinkDB();
                return true;
            });
        }

        if ($this->nb_project_list !== null) {
            $this->nb_project_list->iterate(function($fkey, $nb_project) {
                $nb_project->relinkDB();
                return true;
            });
        }
    }

    /**
     * Overrides refresh method to add Customer subentities to be refreshed.
     * @param bool $force Forces to reload entities from the database storage.
     * @param bool $cascade Forces to reload child entities from the database storage.
     * @return bool Returns true if transations are empty or refreshed.
     */
    public function refresh(bool $force = false, bool $cascade = false)
    {
        return parent::refresh($force, $cascade) &&
               (!$cascade ||
                    (
                        $this->getMediotecas($force) &&
                        $this->getSites($force) &&
                        $this->getCommerces($force) &&
                        $this->getCatalogs($force) &&
                        $this->getMessagings($force) &&
                        $this->getProjects($force)
                    )
               )
        ;
    }

    /**
     * Gets the "visible name" of Customer. Internally, checks by priority order a valid name to show, and if no name
     * available, then returns a "<nonamed>" default value.
     * @return string Returns the located name of customer.
     */
    public function getVisibleName() {

        if ($this->isValueString('cms_customer_fiscal_name')) {
            return $this->getFiscalName();
        } else {
            return '<nonamed>';
        }
    }

    /**
     * Gets available Medioteca instances in the list.
     * @param bool $force If true, forces to merge complete list form the storage.
     * @return array Returns an associative array where the index is the ID of each Medioteca and the value
     * is the instance.
     */
    public function getMediotecas($force = false)
    {
        if ($force) {
            $this->nb_medioteca_list->merge(CNabuMedioteca::getAllMediotecas($this));
        }

        return $this->nb_medioteca_list->getItems();
    }

    /**
     * Get all languages used in the Medioteca set.
     * @return CNabuLanguageList Returns the list of unique languages used.
     */
    public function getMediotecaSetUsedLanguages()
    {
        return CNabuMedioteca::getCustomerUsedLanguages($this);
    }

    /**
     * Gets a Medioteca by their ID.
     * If the internal Medioteca List contains a instance with same ID returns this instance, else if not exists,
     * tries to locate it in the storage and, if exists, then load it, add into Medioteca List and returns their
     * instance as result.
     * @param mixed $nb_medioteca A CNabuDataObject instance containing a nb_medioteca_id field or an ID.
     * @return CNabuMedioteca Returns the Medioteca instance if exists or false if not.
     * @throws ENabuCoreException Raises an exception if $nb_medioteca has no valid value.
     */
    public function getMedioteca($nb_medioteca)
    {
        $retval = false;

        if (is_object($nb_medioteca) && !($nb_medioteca instanceof CNabuDataObject)) {
            throw new ENabuCoreException(
                ENabuCoreException::ERROR_UNEXPECTED_PARAM_CLASS_TYPE,
                array('$nb_medioteca', get_class($nb_medioteca))
            );
        }

        if ($nb_medioteca !== null) {
            $nb_medioteca_id = nb_getMixedValue($nb_medioteca, NABU_MEDIOTECA_FIELD_ID);
            if (is_numeric($nb_medioteca_id) || nb_isValidGUID($nb_medioteca_id)) {
                $retval = $this->nb_medioteca_list->getItem($nb_medioteca_id);
            } elseif ($nb_medioteca_id !== null && $nb_medioteca_id !== false) {
                throw new ENabuCoreException(
                    ENabuCoreException::ERROR_UNEXPECTED_PARAM_VALUE,
                    array('$nb_medioteca', print_r($nb_medioteca, true))
                );
            }
        }

        return $retval;
    }

    /**
     * Gets a Medioteca instance using their key.
     * @param string $key Key of the Medioteca to be retrieved.
     * @return CNabuMedioteca Returns a Medioteca instance if exists or false if not.
     */
    public function getMediotecaByKey(string $key)
    {
        if (strlen($key) === 0) {
            throw new ENabuCoreException(
                ENabuCoreException::ERROR_UNEXPECTED_PARAM_VALUE,
                array('$key', print_r($key, true))
            );
        }

        return $this->nb_medioteca_list->getItem($key, CNabuMediotecaList::INDEX_KEY);
    }

    /**
     * Gets available Site instances in the list.
     * @param bool $force If true, forces to merge complete list form the storage.
     * @return array Returns an associative array where the index is the ID of each Site and the value is the instance.
     */
    public function getSites($force = false)
    {
        if ($force) {
            $this->nb_site_list->merge(CNabuSite::getAllSites($this));
        }

        return $this->nb_site_list->getItems();
    }

    /**
     * Get all languages used in the Site set.
     * @return CNabuLanguageList Returns the list of unique languages used.
     */
    public function getSiteSetUsedLanguages()
    {
        return CNabuSite::getCustomerUsedLanguages($this);
    }

    /**
     * Gets a Site by their ID.
     * If the internal Site List contains a instance with same ID returns this instance, else if not exists,
     * tries to locate it in the storage and, if exists, then load it, add into Site List and returns their
     * instance as result.
     * @param mixed $nb_site A CNabuDataObject instance containing a nb_site_id field or an ID.
     * @return CNabuSite Returns the Site instance if exists or false if not.
     * @throws ENabuCoreException Raises an exception if $nb_site has no valid value.
     */
    public function getSite($nb_site)
    {
        $retval = false;

        if (is_object($nb_site) && !($nb_site instanceof CNabuDataObject)) {
            throw new ENabuCoreException(
                ENabuCoreException::ERROR_UNEXPECTED_PARAM_CLASS_TYPE,
                array('$nb_site', get_class($nb_site))
            );
        }

        if ($nb_site !== null) {
            $nb_site_id = nb_getMixedValue($nb_site, NABU_SITE_FIELD_ID);
            if (is_numeric($nb_site_id) || nb_isValidGUID($nb_site_id)) {
                $retval = $this->nb_site_list->getItem($nb_site_id);
            } elseif ($nb_site_id !== null && $nb_site_id !== false) {
                throw new ENabuCoreException(
                    ENabuCoreException::ERROR_UNEXPECTED_PARAM_VALUE,
                    array('$nb_site', print_r($nb_site, true))
                );
            }
        }

        return $retval;
    }

    /**
     * Gets available Commerce instances in the list.
     * @param bool $force If true, forces to merge complete list from the storage.
     * @return array Returns an associative array where the index is the ID of each Commerce and the value
     * is the instance.
     */
    public function getCommerces($force = false)
    {
        if ($force) {
            $this->nb_commerce_list->merge(CNabuCommerce::getAllCommerces($this));
        }

        return $this->nb_commerce_list->getItems();
    }

    /**
     * Get all languages used in the Commerce set.
     * @return CNabuLanguageList Returns the list of unique languages used.
     */
    public function getCommerceSetUsedLanguages()
    {
        return CNabuCommerce::getCustomerUsedLanguages($this);
    }

    /**
     * Gets a Commerce by their ID.
     * If the internal Commerce List contains a instance with same ID returns this instance, else if not exists,
     * tries to locate it in the storage and, if exists, then load it, add into Commerce List and returns their
     * instance as result.
     * @param mixed $nb_commerce A CNabuDataObject instance containing a nb_commerce_id field or an ID.
     * @return CNabuCommerce Returns the Commerce instance if exists or false if not.
     * @throws ENabuCoreException Raises an exception if $nb_commerce has no valid value.
     */
    public function getCommerce($nb_commerce)
    {
        $retval = false;

        if (is_object($nb_commerce) && !($nb_commerce instanceof CNabuDataObject)) {
            throw new ENabuCoreException(
                ENabuCoreException::ERROR_UNEXPECTED_PARAM_CLASS_TYPE,
                array('$nb_commerce', get_class($nb_commerce))
            );
        }

        if ($nb_commerce !== null) {
            $nb_commerce_id = nb_getMixedValue($nb_commerce, NABU_COMMERCE_FIELD_ID);
            if (is_numeric($nb_commerce_id) || nb_isValidGUID($nb_commerce_id)) {
                $retval = $this->nb_commerce_list->getItem($nb_commerce_id);
            } elseif ($nb_commerce_id !== null && $nb_commerce_id !== false) {
                throw new ENabuCoreException(
                    ENabuCoreException::ERROR_UNEXPECTED_PARAM_VALUE,
                    array('$nb_commerce', print_r($nb_commerce, true))
                );
            }
        }

        return $retval;
    }

    /**
     * Gets a Catalog by their ID.
     * If the internal Catalog List contains a instance with same ID returns this instance, else if not exists,
     * tries to locate it in the storage and, if exits, the load it, add into Catalog List and returns their
     * instance as result.
     * @param mixed $nb_catalog A CNabuDataObject instance containing a nb_catalog_id field or an ID.
     * @return CNabuCatalog Returns the Catalog instance if exists or false if not.
     * @throws ENabuCoreException Raises an exception if $nb_catalog has no valid value.
     */
    public function getCatalog($nb_catalog)
    {
        $retval = false;

        if (is_object($nb_catalog) && !($nb_catalog instanceof CNabuDataObject)) {
            throw new ENabuCoreException(
                ENabuCoreException::ERROR_UNEXPECTED_PARAM_CLASS_TYPE,
                array('$nb_catalog', get_class($nb_catalog))
            );
        }

        if ($nb_catalog !== null) {
            $nb_catalog_id = nb_getMixedValue($nb_catalog, NABU_CATALOG_FIELD_ID);
            if (is_numeric($nb_catalog_id) || nb_isValidGUID($nb_catalog_id)) {
                $retval = $this->nb_catalog_list->getItem($nb_catalog_id);
            } elseif ($nb_catalog_id !== null && $nb_catalog_id !== false) {
                throw new ENabuCoreException(
                    ENabuCoreException::ERROR_UNEXPECTED_PARAM_VALUE,
                    array('$nb_catalog', print_r($nb_catalog, true))
                );
            }
        }

        return $retval;
    }

    /**
     * Gets a Catalog instance using their key.
     * @param string $key Key of the Catalog to be retrieved.
     * @return CNabuCatalog Returns a Catalog instance if exists or false if not.
     */
    public function getCatalogByKey($key)
    {
        if (!is_string($key) || strlen($key) === 0) {
            throw new ENabuCoreException(
                ENabuCoreException::ERROR_UNEXPECTED_PARAM_VALUE,
                array('$key', print_r($key, true))
            );
        }

        return $this->nb_catalog_list->getItem($key, CNabuCatalogList::INDEX_KEY);
    }

    /**
     * Gets available Catalog instances in the list.
     * @param bool $force If true, fordes to merge complete list from the storage.
     * @return array Returns an associative array where the index is the ID of each Catalog and the value
     * is the instance.
     */
    public function getCatalogs($force = false)
    {
        if ($force) {
            $this->nb_catalog_list->merge(CNabuCatalog::getAllCatalogs($this));
        }

        return $this->nb_catalog_list->getItems();
    }

    /**
     * Get all languages used in the Catalog set.
     * @return CNabuLanguageList Returns the list of unique languages used.
     */
    public function getCatalogSetUsedLanguages()
    {
        return CNabuCatalog::getCustomerUsedLanguages($this);
    }

    /**
     * Gets available Messaging instances in the list.
     * @param bool $force If true, foces to merge complete list from the storage.
     * @return array Returns an associative array where the index is the ID of each Catalog and the value
     * is the instance.
     */
    public function getMessagings($force = false)
    {
        if ($force) {
            $this->nb_messaging_list->merge(CNabuMessaging::getAllMessagings($this));
        }

        return $this->nb_messaging_list->getItems();
    }

    /**
     * Get all languages used in the Messaging set.
     * @return CNabuLanguageList Returns the list of unique languages used.
     */
    public function getMessagingSetUsedLanguages()
    {
        return CNabuMessaging::getCustomerUsedLanguages($this);
    }

    /**
     * Gets a Messaging by their ID.
     * If the internal Messaging List contains a instance with same ID returns this instance, else if not exists,
     * tries to locate it in the storage and, if exits, the load it, add into Messaging List and returns their
     * instance as result.
     * @param mixed $nb_messaging A CNabuDataObject instance containing a nb_messaging_id field or an ID.
     * @return CNabuMessaging Returns the Messaging instance if exists or false if not.
     * @throws ENabuCoreException Raises an exception if $nb_messaging has no valid value.
     */
    public function getMessaging($nb_messaging)
    {
        $retval = false;

        if (is_object($nb_messaging) && !($nb_messaging instanceof CNabuDataObject)) {
            throw new ENabuCoreException(
                ENabuCoreException::ERROR_UNEXPECTED_PARAM_CLASS_TYPE,
                array('$nb_messaging', get_class($nb_messaging))
            );
        }

        if ($nb_messaging !== null) {
            $nb_messaging_id = nb_getMixedValue($nb_messaging, NABU_MESSAGING_FIELD_ID);
            if (is_numeric($nb_messaging_id) || nb_isValidGUID($nb_messaging_id)) {
                $retval = $this->nb_messaging_list->getItem($nb_messaging_id);
            } elseif ($nb_messaging_id !== null && $nb_messaging_id !== false) {
                throw new ENabuCoreException(
                    ENabuCoreException::ERROR_UNEXPECTED_PARAM_VALUE,
                    array('$nb_messaging', print_r($nb_messaging, true))
                );
            }
        }

        return $retval;
    }

    /**
     * Gets available Project instances in the list.
     * @param bool $force If true, foces to merge complete list from the storage.
     * @return array Returns an associative array where the index is the ID of each Catalog and the value
     * is the instance.
     */
    public function getProjects($force = false)
    {
        if ($this->nb_project_list === null) {
            $this->nb_project_list = new CNabuProjectList($this);
        }

        if ($force) {
            $this->nb_project_list->merge(CNabuProject::getAllProjects($this));
        }

        return $this->nb_project_list->getItems();
    }

    /**
     * Get all languages used in the Project set.
     * @return CNabuLanguageList Returns the list of unique languages used.
     */
    public function getProjectSetUsedLanguages()
    {
        return CNabuProject::getCustomerUsedLanguages($this);
    }

    /**
     * Gets a Project by their ID.
     * If the internal Project List contains a instance with same ID returns this instance, else if not exists,
     * tries to locate it in the storage and, if exits, the load it, add into Project List and returns their
     * instance as result.
     * @param mixed $nb_project A CNabuDataObject instance containing a nb_project_id field or an ID.
     * @return CNabuProject Returns the Project instance if exists or false if not.
     * @throws ENabuCoreException Raises an exception if $nb_project has no valid value.
     */
    public function getProject($nb_project)
    {
        $retval = false;

        if (is_object($nb_project) && !($nb_project instanceof CNabuDataObject)) {
            throw new ENabuCoreException(
                ENabuCoreException::ERROR_UNEXPECTED_PARAM_CLASS_TYPE,
                array('$nb_project', get_class($nb_project))
            );
        }

        if ($nb_project !== null) {
            $nb_project_id = nb_getMixedValue($nb_project, NABU_PROJECT_FIELD_ID);
            if (is_numeric($nb_project_id) || nb_isValidGUID($nb_project_id)) {
                $retval = $this->nb_project_list->getItem($nb_project_id);
            } elseif ($nb_project_id !== null && $nb_project_id !== false) {
                throw new ENabuCoreException(
                    ENabuCoreException::ERROR_UNEXPECTED_PARAM_VALUE,
                    array('$nb_project', print_r($nb_project, true))
                );
            }
        }

        return $retval;
    }
}
