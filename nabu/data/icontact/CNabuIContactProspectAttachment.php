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
use nabu\data\CNabuDataObject;

use nabu\core\exceptions\ENabuCoreException;
use nabu\data\icontact\base\CNabuIContactProspectAttachmentBase;
use nabu\data\icontact\traits\TNabuIContactProspectChild;
use nabu\data\icontact\exceptions\ENabuIContactException;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\data\icontact
 */
class CNabuIContactProspectAttachment extends CNabuIContactProspectAttachmentBase
{
    use TNabuIContactProspectChild;

    /**
     * Get the valid file name of this attachment. Returned value does not grant that the file exists.
     * @return string The name of the file well formed without his base path.
     */
    public function getFilename()
    {
        return str_replace(array('{', '}'), '', strtolower($this->getHash()));
    }

    public function getLocalFilename()
    {
        $retval = null;

        if (($nb_icontact_prospect = $this->getIContactProspect()) instanceof CNabuIContactProspect &&
            ($nb_icontact = $nb_icontact_prospect->getIContact()) instanceof CNabuIContact &&
            strlen($hash = $this->getHash()) > 0 &&
            strlen($base_path = $nb_icontact->getBasePath())
        ) {
            $retval = $base_path . DIRECTORY_SEPARATOR . $this->getFilename();
        }

        return $retval;
    }

    /**
     * Put the file associated to this attachment in the iContact storage.
     * @param string $sourcefile Source path of the file to be attached.
     * @throws ENabuIContactException Raises an exception if source file does not exists or cannot be moved.
     */
    public function putFile(string $sourcefile)
    {
        if (file_exists($sourcefile)) {
            if (($nb_icontact_prospect = $this->getIContactProspect()) instanceof CNabuIContactProspect &&
                ($nb_icontact = $nb_icontact_prospect->getIContact()) instanceof CNabuIContact
            ) {
                $this->grantHash();
                $nb_icontact->grantStorageFolder();
                $base_path = $nb_icontact->getBasePath();
                move_uploaded_file($sourcefile, $base_path . DIRECTORY_SEPARATOR . $this->getFilename());
            } else {
                throw new ENabuIContactException(ENabuIContactException::ERROR_ICONTACT_NOT_FOUND);
            }
        } else {
            throw new ENabuCoreException(ENabuCoreException::ERROR_FILE_NOT_FOUND, array($sourcefile));
        }
    }

    /**
     * Delete the file of this attachment from the iContact storage.
     * @return bool Returns true if success.
     */
    public function deleteFile()
    {
        $retval = false;

        if (($nb_icontact_prospect = $this->getIContactProspect()) instanceof CNabuIContactProspect &&
            ($nb_icontact = $nb_icontact_prospect->getIContact()) instanceof CNabuIContact
        ) {
            $base_path = $nb_icontact->getBasePath();
            $filename = $base_path . DIRECTORY_SEPARATOR . $this->getFilename();

            if (file_exists($filename)) {
                unlink($filename);
                $retval = !file_exists($filename);
            }
        }

        return $retval;
    }

    /**
     * Get a list of all attachments associated to an iContact Prospect instance.
     * @param mixed $nb_prospect A CNabuDataObject containing a field named nb_icontact_prospect_id or a valid Id.
     * @return CNabuIContactProspectAttachmentList Returns the list with found attachments.
     */
    public static function getAttachmentsForProspect($nb_prospect)
    {
        if (is_numeric($nb_prospect_id = nb_getMixedValue($nb_prospect, NABU_ICONTACT_PROSPECT_FIELD_ID))) {
            $retval = CNabuIContactProspectAttachment::buildObjectListFromSQL(
                NABU_ICONTACT_PROSPECT_ATTACHMENT_FIELD_ID,
                'SELECT ipa.*
                   FROM nb_icontact_prospect_attachment ipa, nb_icontact_prospect ip
                  WHERE ipa.nb_icontact_prospect_id=ip.nb_icontact_prospect_id
                    AND ip.nb_icontact_prospect_id=%prospect_id$d
                  ORDER BY ipa.nb_icontact_prospect_attachment_id',
                array(
                    'prospect_id' => $nb_prospect_id
                ),
                ($nb_prospect instanceof CNabuIContactProspect ? $nb_prospect : null)
            );

            if ($nb_prospect instanceof CNabuIContactProspect) {
                $retval->iterate(
                    function($key, CNabuIContactProspectAttachment $nb_attachment) use ($nb_prospect)
                    {
                        $nb_attachment->setIContactProspect($nb_prospect);
                        return true;
                    }
                );
            }
        } else {
            $retval = new CNabuIContactProspectList(($nb_prospect instanceof CNabuIContactProspect ? $nb_prospect : null));
        }

        return $retval;
    }
}
