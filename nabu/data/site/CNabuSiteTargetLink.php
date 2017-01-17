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

use \nabu\data\CNabuDataObject;
use \nabu\core\CNabuObject;
use \nabu\core\exceptions\ENabuCoreException;
use \nabu\http\CNabuHTTPRequest;
use nabu\core\CNabuEngine;
use nabu\data\lang\CNabuLanguageList;

/**
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\site
 */
class CNabuSiteTargetLink extends CNabuObject
{
    /**
     * The Link is a INabuTranslated instance
     */
    const USE_URI_TRANSLATED = 'T';
    /**
     * The Link is a URL
     */
    const USE_URI_URL = 'U';
    /**
     * The Link is not setted
     */
    const USE_URI_NONE = 'N';

    /**
     * Kind of link.
     * @var string
     */
    private $kind = self::USE_URI_NONE;
    private $nb_site_target = null;
    private $nb_translated = null;
    private $nb_translated_lang_field = null;
    private $url = null;

    public function __construct($kind = null, $source = null, $translated_lang_field = null)
    {
        parent::__construct();
        if ($kind === null || $source === null) {
            $this->kind = self::USE_URI_NONE;
            $this->nb_translated = null;
            $this->nb_translated_lang_field = null;
            $this->url = null;
        } else {
            switch ($kind) {
                case self::USE_URI_TRANSLATED:
                    if (!($source instanceof CNabuSiteTarget)) {
                        throw new ENabuCoreException(
                            ENabuCoreException::ERROR_UNEXPECTED_PARAM_CLASS_TYPE,
                            array(get_class($source), '$source')
                        );
                    } elseif (!is_string($translated_lang_field) || strlen($translated_lang_field) === 0) {
                        throw new ENabuCoreException(
                            ENabuCoreException::ERROR_UNEXPECTED_PARAM_VALUE,
                            array($translated_lang_field, '$translated_lang_field')
                        );
                    } else {
                        $this->kind = self::USE_URI_TRANSLATED;
                        $this->nb_translated = $source;
                        $this->nb_translated_lang_field = $translated_lang_field;
                        $this->url = null;
                    }
                    break;
                case self::USE_URI_URL:
                    if (!is_string($source) || strlen($source) === 0) {
                        throw new ENabuCoreException(
                            ENabuCoreException::ERROR_UNEXPECTED_PARAM_VALUE,
                            array($source, '$source')
                        );
                    } else {
                        $this->kind = self::USE_URI_URL;
                        $this->nb_site_target = null;
                        $this->url = $source;
                    }
                    break;
                case self::USE_URI_NONE:
                    $this->kind = self::USE_URI_NONE;
                    $this->nb_site_target = null;
                    $this->url = null;
                    break;
                default:
                    throw new ENabuCoreException(
                        ENabuCoreException::ERROR_UNEXPECTED_PARAM_VALUE,
                        array($kind, '$kind')
                    );
            }
        }
    }

    public static function buildLinkFromReferredInstance(
        CNabuSite $nb_site,
        CNabuDataObject $nb_instance,
        $field_use,
        $field_target_id,
        $field_lang_url
    ) {
        $link = null;
        $use_uri = $nb_instance->getValue($field_use);
        switch ($use_uri) {
            case self::USE_URI_TRANSLATED:
                $link = self::buildLinkFromTarget(
                    $nb_site->getTarget($nb_instance->getValue($field_target_id)),
                    $field_lang_url
                );
                break;
            case self::USE_URI_URL:
                $link = self::buildLinkFromURL($nb_instance->getValue($field_lang_url));
                break;
            default:
                $link = new CNabuSiteTargetLink();
        }

        return $link;
    }

    public static function buildLinkFromTarget(CNabuSiteTarget $nb_site_target, $translated_lang_field)
    {
        return new CNabuSiteTargetLink(self::USE_URI_TRANSLATED, $nb_site_target, $translated_lang_field);
    }

    public static function buildLinkFromURL($url)
    {
        return new CNabuSiteTargetLink(self::USE_URI_URL, $url);
    }

    public function isLinkable()
    {
        return ($this->kind === self::USE_URI_URL && $this->url !== null) ||
               ($this->kind === self::USE_URI_TRANSLATED &&
                $this->nb_translated !== null &&
                $this->nb_translated_lang_field !== null
               )
        ;
    }

    public function getBestQualifiedURL($accept_languages = null)
    {
        $url = false;

        switch ($this->kind) {
            case self::USE_URI_URL:
                $url = $this->url;
                break;
            case self::USE_URI_TRANSLATED:
                $url = $this->getBestQualifiedTranslation($accept_languages);
                break;
        }

        return $url;
    }

    private function getBestQualifiedTranslation($accept_languages = null)
    {
        $nb_request = CNabuEngine::getEngine()->getApplication()->getRequest();
        $nb_site = $nb_request->getSite();

        $nb_language = null;
        $url = false;

        if (is_array($accept_languages) &&
            count($accept_languages) > 0 &&
            $nb_site->hasLanguages() &&
            $this->nb_translated->hasTranslations()
        ) {
            $nb_languages = $nb_site->getLanguages();
            $lang_keys = array_intersect($nb_languages->getKeys(), $this->nb_translated->getTranslations()->getKeys());
            if (count($lang_keys) > 0 &&
                ($nb_language = $this->matchLanguages($nb_languages, $lang_keys, $accept_languages)) === null
            ) {
                $nb_language = $nb_site->getDefaultLanguage();
            }
        } elseif ($accept_languages instanceof CNabuDataObject && $accept_languages->contains(NABU_LANG_FIELD_ID)) {
            $nb_language = $accept_languages->getValue(NABU_LANG_FIELD_ID);
        } else {
            $nb_language = $nb_request->getLanguage();
            if ($nb_language === null) {
                $nb_language = $nb_site->getDefaultLanguage();
            }
        }

        if ($nb_language !== null) {
            if ($this->nb_translated instanceof CNabuSiteTarget) {
                $url = $this->nb_translated->getFullyQualifiedURL($nb_language);
            } else {
                $translation = $this->nb_translated->getTranslation($nb_language);
                $url = $translation->getValue($this->nb_translated_lang_field);
            }
        }

        return $url;
    }

    private function matchLanguages(CNabuLanguageList $nb_languages, $lang_keys, $accept_languages)
    {
        $nb_language = null;

        foreach (array_keys($accept_languages) as $accept) {
            foreach ($lang_keys as $key) {
                $nb_aux = $nb_languages->getItem($key);
                if ($nb_aux->isEnabled() &&
                    ($nb_aux->getISO6391() === $accept || $nb_aux->getDefaultCountryCode() === $accept)
                ) {
                    $nb_language = $nb_aux;
                    break;
                }
            }
            if ($nb_language !== null) {
                break;
            }
        }

        return $nb_language;
    }

    public function matchTarget($target)
    {
        $retval = false;

        if ($target instanceof CNabuSiteTarget &&
            $this->kind === self::USE_URI_TRANSLATED &&
            $this->nb_translated instanceof CNabuSiteTarget
        ) {
            $retval = $target->matchValue($this->nb_translated, 'nb_site_target_id');
        } elseif (is_string($target) &&
            $this->kind === self::USE_URI_URL &&
            is_string($this->url)
        ) {
            $retval = ($target === $this->url);
        } else {
            throw new ENabuCoreException(ENabuCoreException::ERROR_FEATURE_NOT_IMPLEMENTED);
        }

        return $retval;
    }

    public function isTranslatedTarget()
    {
        return $this->kind === self::USE_URI_TRANSLATED && $this->nb_translated instanceof CNabuSiteTarget;
    }

    public function getTranslatedObject()
    {
        return $this->kind === self::USE_URI_TRANSLATED ? $this->nb_translated : null;
    }
}
