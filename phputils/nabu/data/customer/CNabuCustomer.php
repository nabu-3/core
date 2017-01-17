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
use nabu\data\medioteca\CNabuMedioteca;
use nabu\data\medioteca\CNabuMediotecaList;
use nabu\data\site\CNabuSite;
use nabu\data\site\CNabuSiteList;

/**
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\customer
 */
class CNabuCustomer extends CNabuCustomerBase
{
    /**
     * List of Mediotecas. This list can be filled only with requested Mediotecas (on demand) or with a full list.
     * @var CNabuMediotecaList
     */
    private $nb_medioteca_list;
    /**
     * Lit of Sites. This list can be filled only with requested Sites (on demand) or with a full list.
     * @var CNabuSiteList
     */
    private $nb_site_list;
    /**
     * List of Commerces. This list can be filled only with requested Commerces (on demand) or with a full list.
     * @var CNabuCommerceList
     */
    private $nb_commerce_list;
    /**
     * List of Catalogs. This list can be filled only with requested Catalogs (on demand) or with a full list.
     * @var CNabuCatalogList
     */
    private $nb_catalog_list;

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
     * @ return CNabuLanguageList Returns the list of unique languages used.
     */
    public function getCatalogSetUsedLanguages()
    {
        return CNabuCatalog::getCustomerUsedLanguages($this);
    }
}