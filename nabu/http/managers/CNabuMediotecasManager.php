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

namespace nabu\http\managers;

use \nabu\data\medioteca\CNabuMediotecaList;
use nabu\core\CNabuEngine;
use nabu\core\exceptions\ENabuCoreException;
use nabu\data\customer\CNabuCustomer;
use nabu\data\medioteca\CNabuMedioteca;
use nabu\http\app\base\CNabuHTTPApplication;
use nabu\http\managers\base\CNabuHTTPManager;

/**
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \nabu\http\managers
 */
class CNabuMediotecasManager extends CNabuHTTPManager
{
    /**
     * Mediteca instances collection
     * @var CNabuMediotecaList
     */
    private $nb_medioteca_list = null;
    /**
     * Customer owner instance
     * @var CNabuCustomer
     */
    private $nb_customer = null;

    /**
     * Default constructor.
     * @param CNabuHTTPApplication $nb_application Application instance
     */
    public function __construct(CNabuHTTPApplication $nb_application, CNabuCustomer $nb_customer = null)
    {
        parent::__construct($nb_application);

        $this->nb_customer = ($nb_customer !== null ? $nb_customer : CNabuEngine::getEngine()->getCustomer());
        $this->nb_medioteca_list = new CNabuMediotecaList($this->nb_customer);
    }

    /**
     * Register the provider in current application to extend their functionalities.
     * @return bool Returns true if enable process is succeed.
     */
    protected function enableManager()
    {
        return true;
    }

    public function addMedioteca(CNabuMedioteca $nb_medioteca)
    {
        if ($nb_medioteca->validateCustomer($this->nb_customer)) {
            $this->nb_medioteca_list->addItem($nb_medioteca);
        } else {
            throw new ENabuCoreException(ENabuCoreException::ERROR_CUSTOMERS_DOES_NOT_MATCH);
        }

        return $nb_medioteca;
    }

    /**
     * Gets a Medioteca Instance.
     * @param mixed $nb_medioteca A CNabuDataObject intance containing a field named 'nb_medioteca_id' or and ID.
     * @return mixed Returns the instance if exists or false if not.
     */
    public function getMedioteca($nb_medioteca)
    {
        $nb_medioteca_id = nb_getMixedValue($nb_medioteca, NABU_MEDIOTECA_FIELD_ID);

        return is_numeric($nb_medioteca_id) || nb_isValidGUID($nb_medioteca_id)
               ? $this->nb_medioteca_list->getItem($nb_medioteca_id)
               : false
        ;
    }

    /**
     * Gets the collection of Mediotecas
     * @param bool $force If true reload the medioteca collection from the database.
     * @return CNabuMediotecaList Return the list instance of Mediotecas.
     */
    public function getMediotecas($force = false)
    {
        if ($this->nb_medioteca_list->isEmpty() || $force) {
            $this->nb_medioteca_list->merge(
                CNabuMedioteca::getMediotecasForCustomer(CNabuEngine::getEngine()->getCustomer())
            );
        }
        return $this->nb_medioteca_list;
    }

    public function indexAll()
    {
        $this->nb_medioteca_list->sort();

        $this->nb_medioteca_list->iterate(
            function ($key, $medioteca) {
                $medioteca->indexAll();
                return true;
            }
        );
    }

    public function getKeysIndex()
    {
        return $this->nb_medioteca_list->getIndex(CNabuMediotecaList::INDEX_KEY);
    }
}
