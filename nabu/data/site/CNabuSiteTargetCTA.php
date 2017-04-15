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
use nabu\data\security\CNabuRole;
use nabu\data\security\interfaces\INabuRoleMask;
use nabu\data\site\CNabuSiteTarget;
use nabu\data\site\CNabuSiteTargetLink;
use nabu\data\site\base\CNabuSiteTargetCTABase;
use nabu\http\app\base\CNabuHTTPApplication;
use nabu\http\managers\CNabuHTTPSecurityManager;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\site
 */
class CNabuSiteTargetCTA extends CNabuSiteTargetCTABase implements INabuRoleMask
{
    /**
     * Site Target destination of this CTA.
     * @var CNabuSiteTarget
     */
    private $nb_site_target_destination = null;
    /**
     * Site Target CTA Role list.
     * @var CNabuSiteTargetCTARoleList
     */
    private $nb_site_target_cta_role_list = null;

    public function __construct($nb_site_target_cta = false)
    {
        parent::__construct($nb_site_target_cta);
        $this->nb_site_target_cta_role_list = new CNabuSiteTargetCTARoleList($this);
    }

    public function getBestQualifiedURL($nb_language)
    {
        $url = null;

        if ($this->nb_site_target !== null &&
            ($nb_application = CNabuEngine::getEngine()->getApplication()) !== null &&
            ($nb_application instanceof CNabuHTTPApplication) &&
            ($nb_request = $nb_application->getRequest()) !== null &&
            ($nb_site = $nb_request->getSite()) !== null
        ) {
            $nb_site_target_link = CNabuSiteTargetLink::buildLinkFromReferredInstance(
                $nb_site,
                $this,
                'nb_site_target_cta_target_use_uri',
                'nb_site_target_cta_target_id',
                'nb_site_target_cta_lang_target_url'
            );

            $url = $nb_site_target_link->getBestQualifiedURL($nb_language);
        }

        return $url;
    }

    /**
     * Gets entire collection of CTA instances of a Site Target.
     * @param mixed $nb_site_target A Site Target instance, an object containing a Site Target Id, or an Id.
     * @return CNabuSiteTargetCTAList Returns a CNabuSiteTargetCTAList instance containing located
     * CTAs for $nb_site_target.
     */
    static public function getSiteTargetCTAs($nb_site_target)
    {
        $nb_site_target_id = nb_getMixedValue($nb_site_target, 'nb_site_target_id');
        if (is_numeric($nb_site_target_id)) {
            $retval = CNabuSiteTargetCTA::buildObjectListFromSQL(
                'nb_site_target_cta_id',
                'select * '
                . 'from nb_site_target_cta '
               . 'where nb_site_target_id=%target_id$d '
               . 'order by nb_site_target_cta_order',
                array(
                    'target_id' => $nb_site_target_id
                )
            );
        } else {
            $retval = new CNabuSiteTargetCTAList();
        }

        return $retval;
    }

    /**
     * Gets the Site Target destination instance.
     * @return CNabuSiteTarget Returns the instance if setted or null if not.
     */
    public function getCTATarget()
    {
        return $this->nb_site_target_destination;
    }

    public function setCTATarget(CNabuSiteTarget $nb_site_target)
    {
        $this->setTargetUseURI(CNabuSiteTargetLink::USE_URI_TRANSLATED);
        $this->transferValue($nb_site_target, 'nb_site_target_cta_target_id', 'nb_site_target_id');
        $this->nb_site_target_destination = $nb_site_target;

        return $this;
    }

    public function isUsingURIAsTarget()
    {
        return $this->getTargetUseURI() === CNabuSiteTargetLink::USE_URI_TRANSLATED;
    }

    public function isUsingURIAsURL()
    {
        return $this->getTargetUseURI() === CNabuSiteTargetLink::USE_URI_URL;
    }

    public function isURIUsed()
    {
        return $this->getTargetUseURI() !== CNabuSiteTargetLink::USE_URI_NONE;
    }

    /**
     * Add a Role to this Site Target CTA instance.
     * @param CNabuSiteTargetCTARole $nb_role Role to be added.
     * @return CNabuSiteTargetCTARole Returns the inserted Role instance.
     */
    public function addRole(CNabuSiteTargetCTARole $nb_role)
    {
        if ($nb_role->isValueNumeric(NABU_ROLE_FIELD_ID) || $nb_role->isValueGUID(NABU_ROLE_FIELD_ID)) {
            $nb_role->setSiteTargetCTA($this);
            $this->nb_site_target_cta_role_list->addItem($nb_role);
        }

        return $nb_role;
    }

    public function applyRoleMask(CNabuRole $nb_role, array $additional = null)
    {
        $nb_role_id = $nb_role->getId();
        $nb_role_mask = $this->nb_site_target_cta_role_list->getItem($nb_role_id);
        $this->nb_site_target_cta_role_list->clear();
        if ($nb_role_mask instanceof CNabuSiteTargetCTARole) {
            if (is_array($additional) &&
                array_key_exists(CNabuHTTPSecurityManager::ROLE_MASK_USER_SIGNED, $additional) &&
                is_bool($additional[CNabuHTTPSecurityManager::ROLE_MASK_USER_SIGNED])
            ) {
                $user_signed = $additional[CNabuHTTPSecurityManager::ROLE_MASK_USER_SIGNED];
                if (($user_signed && $nb_role_mask->isForPrivateZone()) ||
                    (!$user_signed && $nb_role_mask->isForPublicZone())
                ) {
                    $this->nb_site_target_cta_role_list->addItem($nb_role_mask);
                }
            }
        }

        return $this->nb_site_target_cta_role_list->isFilled();
    }

    public function canonize()
    {
        $translations = $this->getTranslations();

        $translations->iterate(
            function ($key, $nb_site_target_cta_translation)
            {
                if ($this->isUsingURIAsTarget() && $this->nb_site_target_destination !== null) {
                    $nb_site_target_cta_translation->setFinalURL(
                        $this->nb_site_target_destination->getFullyQualifiedURL($nb_site_target_cta_translation)
                    );
                } elseif ($this->isUsingURIAsURL()) {
                    $nb_site_target_cta_translation->setFinalURL($nb_site_target_cta_translation->getTargetURL());
                }
            }
        );
    }

    public function getTreeData($nb_language = null, $dataonly = false)
    {
        $trdata = parent::getTreeData($nb_language, $dataonly);
        $trdata['roles'] = $this->nb_site_target_cta_role_list;

        return $trdata;
    }
}
