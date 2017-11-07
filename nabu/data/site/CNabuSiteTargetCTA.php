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

use nabu\core\CNabuEngine;
use nabu\data\CNabuDataObject;
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
            if ($nb_site_target instanceof CNabuSiteTarget) {
                $retval->iterate(function($key, $nb_cta) use($nb_site_target) {
                    $nb_cta->setSiteTarget($nb_site_target);
                    return true;
                });
            }
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

    /**
     * Sets the CTA Target object. Updates nb_site_target_cta_target_id and nb_site_target_cta_use_uri to proper values.
     * @param CNabuSiteTarget|null $nb_site_target If $nb_site_target contains an instance then sets this instance
     * as the CTA Target else sets the CTA Target as none.
     * @return CNabuSiteTargetCTA Returns the self instance to grant cascade setters mechanism.
     */
    public function setCTATarget(CNabuSiteTarget $nb_site_target = null)
    {
        if ($nb_site_target instanceof CNabuSiteTarget) {
            $this->setTargetUseURI(CNabuSiteTargetLink::USE_URI_TRANSLATED);
            $this->transferValue($nb_site_target, 'nb_site_target_id', 'nb_site_target_cta_target_id');
        } else {
            $this->setTargetUseURI(CNabuSiteTargetLink::USE_URI_NONE);
            $this->setTargetId(null);
        }
        $this->nb_site_target_destination = $nb_site_target;

        return $this;
    }

    /**
     * Empty the CTA Target destination. This is an ossia method to call setCTATarget(null).
     * @return CNabuSiteTargetCTA Returns the self instance to grant cascade setters mechanism.
     */
    public function emptyCTATarget()
    {
        $this->setCTATarget(null);
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

    public function getRoles($force = false)
    {
        if ($this->nb_site_target_cta_role_list->isEmpty() || $force) {
            $this->nb_site_target_cta_role_list->fillFromCTA();
        }

        return $this->nb_site_target_cta_role_list;
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
                return true;
            }
        );
    }

    public function getTreeData($nb_language = null, $dataonly = false)
    {
        $trdata = parent::getTreeData($nb_language, $dataonly);
        $trdata['roles'] = $this->nb_site_target_cta_role_list;

        return $trdata;
    }

    /**
     * Gets a CTA instance related with a Site.
     * @param mixed $nb_site A Site instance, or a CNabuDataObject instance containing a field named nb_site_id or an Id.
     * @param mixed $nb_site_target_cta A Site Target CTA instance, or a CNabuDataObject instance containing a field
     * named nb_site_target_cta_id or an Id.
     * @return CNabuSiteTargetCTA | null Returns an instance if found or null if not.
     */
    public static function getCTAOfSite($nb_site, $nb_site_target_cta)
    {
        $retval = null;

        if (is_numeric($nb_site_id = nb_getMixedValue($nb_site, NABU_SITE_FIELD_ID)) &&
            is_numeric($nb_site_target_cta_id = nb_getMixedValue($nb_site_target_cta, NABU_SITE_TARGET_CTA_FIELD_ID))
        ) {
            $retval = CNabuSiteTargetCTA::buildObjectFromSQL(
                'select stc.* '
                . 'from nb_site s, nb_site_target st, nb_site_target_cta stc '
               . 'where s.nb_site_id=st.nb_site_id '
                 . 'and st.nb_site_target_id=stc.nb_site_target_id '
                 . 'and s.nb_site_id=%site_id$d '
                 . 'and stc.nb_site_target_cta_id=%cta_id$d',
                array(
                    'site_id' => $nb_site_id,
                    'cta_id' => $nb_site_target_cta_id
                )
            );
        }

        return $retval;
    }
}
