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

namespace nabu\provider\base;
use nabu\data\CNabuDataObject;
use nabu\provider\exceptions\ENabuProviderException;
use nabu\provider\interfaces\INabuProviderManager;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.0 Surface
 * @version 3.0.9 Surface
 * @package \nabu\http\managers\base
 */
abstract class CNabuProviderModuleManagerAdapter extends CNabuDataObject implements INabuProviderManager
{
    /** @var string Vendor Key field name */
    const VENDOR_KEY = 'vendor_key';
    /** @var string Module Key field name */
    const MODULE_KEY = 'module_key';
    /** @var string Complex Key field name */
    const COMPLEX_KEY = 'complex_key';

    public function __construct(string $vendor_key, string $module_key)
    {
        parent::__construct();
        
        $this->setVendorKey($vendor_key);
        $this->setModuleKey($module_key);
    }

    public function getVendorKey()
    {
        return $this->getValue(self::VENDOR_KEY);
    }

    /**
     * Sets the vendor key
     * @param string $vendor_key New vendor key.
     * @return INabuProviderManager Returns manager instance to grant cascade calls.
     * @throws ENabuProviderException Raises an exception if $vendor_key have an invalid value.
     */
     public function setVendorKey(string $vendor_key)
     {
         if (!nb_isValidKey($vendor_key)) {
             throw new ENabuProviderException(ENabuProviderException::ERROR_VENDOR_KEY_NOT_VALID, array($vendor_key));
         }
         $this->setValue(self::VENDOR_KEY, $vendor_key);

         try {
             $this->getComplexKey();
         } catch (ENabuProviderException $ex) {

         }

         return $this;
     }

    public function getModuleKey()
    {
        return $this->getValue(self::MODULE_KEY);
    }

    /**
     * Sets the module key
     * @param string $module_key New module key.
     * @return INabuProviderManager Returns the manager instance to grant cascade calls.
     * @throws ENabuProviderException Raises an exception if $module_key have an invalid value.
     */
    public function setModuleKey(string $module_key)
    {
        if (!nb_isValidKey($module_key)) {
            throw new ENabuProviderException(ENabuProviderException::ERROR_MODULE_KEY_NOT_VALID, array($module_key));
        }
        $this->setValue(self::MODULE_KEY, $module_key);

        try {
            $this->getComplexKey();
        } catch (ENabuProviderException $ex) {

        }

        return $this;
    }

    public function getComplexKey()
    {
        if (!$this->hasValue(self::COMPLEX_KEY)) {
            $vendor_key = $this->getVendorKey();
            $module_key = $this->getModuleKey();

            if (nb_isValidKey($vendor_key) && nb_isValidKey($module_key)) {
                $this->setValue(self::COMPLEX_KEY, "$vendor_key:$module_key");
            } else {
                throw new ENabuProviderException(ENabuProviderException::ERROR_INVALID_KEYS);
            }
        }

        return $this->getValue(self::COMPLEX_KEY);
    }
}
