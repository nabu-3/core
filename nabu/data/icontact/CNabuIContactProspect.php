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

namespace nabu\data\icontact;
use nabu\core\CNabuEngine;

use nabu\core\exceptions\ENabuCoreException;
use nabu\data\CNabuDataObject;
use nabu\data\icontact\base\CNabuIContactProspectBase;
use nabu\data\icontact\traits\TNabuIContactChild;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\data\icontact
 */
class CNabuIContactProspect extends CNabuIContactProspectBase
{
    use TNabuIContactChild;

    /** @var string Prefix to be used when build the encoded email. */
    const EMAIL_PREF = 'nasn2293';
    /** @var string Suffix to be used when build the encoded email. */
    const EMAIL_SUFF = '935nkwnf';

    /** @var CNabuIContact Nabu IContact owner instance of this Prospect. */
    private $nb_icontact = null;

    /**
     * Gets the IContact owner instance.
     * @return CNabuIContact|null Returns the IContact assigned owner instance.
     **/
    public function getIContact()
    {
        return $this->nb_icontact;
    }

    /**
     * Sets the IContact owner instance.
     * @param CNabuIContact|null $nb_icontact The IContact Owner to be setted or null to unset current Owner.
     * @return CNabuIContactProspect Returns self instance to grant chained setters mechanism.
     */
    public function setIContact(CNabuIContact $nb_icontact = null) : CNabuIContactProspect
    {
        $this->nb_icontact = $nb_icontact;
        return $this;
    }

    /**
     * Get related Prospects of a User.
     * @param mixed $nb_icontact IContact instance where to find Prospects or a CNabuDataObject containing a field
     * called nb_icontact_prospect_id or an ID.
     * @param mixed $nb_user User instance that holds Prospects or a CNabuDataObject containing a field called
     * nb_user_id or an ID.
     * @param CNabuIContactProspectStatusType|null $nb_status_type If setted, the list is filtered using this status.
     * @return CNabuIContactProspectList Returns a Prospect List containing all Prospects found.
     */
    static public function getProspectsOfUser(
        $nb_icontact, $nb_user,
        CNabuIContactProspectStatusType $nb_status_type = null
    ) : CNabuIContactProspectList
    {
        if (is_numeric($nb_icontact_id = nb_getMixedValue($nb_icontact, 'nb_icontact_id')) &&
            is_numeric($nb_user_id = nb_getMixedValue($nb_user, 'nb_user_id'))
        ) {
            $status_id = nb_getMixedValue($nb_status_type, 'nb_icontact_prospect_status_type_id');
            $retval = CNabuIContactProspect::buildObjectListFromSQL(
                'nb_icontact_prospect_id',
                'SELECT ip.*
                   FROM nb_icontact_prospect ip, nb_icontact i
                  WHERE ip.nb_icontact_id=i.nb_icontact_id
                    AND i.nb_icontact_id=%cont_id$d
                    AND ip.nb_user_id=%user_id$d '
                . (is_numeric($status_id) ? 'AND ip.nb_icontact_prospect_status_id=%status_id$d ' : '')
               . 'ORDER BY ip.nb_icontact_prospect_creation_datetime DESC',
                array(
                    'cont_id' => $nb_icontact_id,
                    'user_id' => $nb_user_id,
                    'status_id' => $status_id
                ),
                ($nb_icontact instanceof CNabuIContact ? $nb_icontact : null)
            );
            if ($nb_icontact instanceof CNabuIContact) {
                $retval->iterate(function ($key, $nb_prospect) use($nb_icontact) {
                    $nb_prospect->setIContact($nb_icontact);
                    return true;
                });
            }
        } else {
            $retval = new CNabuIContactProspectList();
        }

        return $retval;
    }

    /**
     * Get the count of related Prospects of a User.
     * @param mixed $nb_icontact A CNabuDataObject containing a field called nb_icontact_prospect_id or an ID
     * where to count Prospects.
     * @param mixed $nb_user User instance that holds Prospects or a CNabuDataObject containing a field called
     * nb_user_id or an ID.
     * @param CNabuIContactProspectStatusType|null $nb_status_type If setted, the list is filtered using this status.
     * @return int Returns the count of all Prospects found.
     */
    static public function getCountProspectsOfUser(
        $nb_icontact, $nb_user,
        CNabuIContactProspectStatusType $nb_status_type = null
    ) : int
    {
        if (is_numeric($nb_icontact_id = nb_getMixedValue($nb_icontact, 'nb_icontact_id')) &&
            is_numeric($nb_user_id = nb_getMixedValue($nb_user, 'nb_user_id'))
        ) {
            $status_id = nb_getMixedValue($nb_status_type, 'nb_icontact_prospect_status_type_id');
            $retval = CNabuEngine::getEngine()->getMainDB()->getQueryAsCount(
                'nb_icontact_prospect ip, nb_icontact i',
                "ip.nb_icontact_id=i.nb_icontact_id AND i.nb_icontact_id=$nb_icontact_id"
                ." AND ip.nb_user_id=$nb_user_id"
                . (is_numeric($status_id) ? " AND ip.nb_icontact_prospect_status_id=$status_id " : '')
            );
        } else {
            $retval = 0;
        }

        return $retval;
    }


    /**
     * Encodes a clear email using the nabu-3 algorithm. This algorithm is not reversible.
     * @param string $email Email string to be encoded.
     * @return string Returns the encoded Email as string.
     */
    static public function encodeEmail(string $email) : string
    {
        return md5(self::EMAIL_PREF . preg_replace('/\\s/', '', mb_strtolower($email)) . self::EMAIL_SUFF);
    }

    /**
     * Sets the Email and encoding it into a nabu-3 hashing algorithm.
     * @param string|null $email Email string to be encoded and setted.
     * @return CNabuDataObject Returns the self instance to grant cascade setters.
     */
    public function setEmail(string $email = null) : CNabuDataObject
    {
        parent::setEmail($email);
        return parent::setEmailHash(is_string($email) ? self::encodeEmail($email) : null);
    }

    /**
     * To find a list of Prospects related by the same Email hash.
     * @param CNabuIContact $nb_icontact IContact instance that contains requested Prospects.
     * @param string $hash Hash that identifies the Email.
     * @return CNabuIContactProspectList The list of Prospects found.
     */
    static public function findIContactProspectsByEmailHash(CNabuIContact $nb_icontact, string $hash) : CNabuIContactProspectList
    {
        if (is_numeric($nb_icontact_id = nb_getMixedValue($nb_icontact, 'nb_icontact_id')) &&
            strlen($hash) === 32
        ) {
            $list = CNabuIContactProspect::buildObjectListFromSQL(
                'nb_icontact_prospect_id',
                'SELECT *
                   FROM nb_icontact_prospect
                  WHERE nb_icontact_id=%icontact_id$d
                    AND nb_icontact_prospect_email_hash=\'%hash$s\'',
                array(
                    'icontact_id' => $nb_icontact_id,
                    'hash' => $hash
                )
            );
        } else {
            throw new ENabuCoreException(ENabuCoreException::ERROR_UNEXPECTED_PARAM_VALUE, array('$hash'));
        }

        return $list;
    }
}
