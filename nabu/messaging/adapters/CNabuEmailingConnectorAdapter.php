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

namespace nabu\messaging\adapters;
use nabu\messaging\interfaces\INabuEmailingConnector;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.9 Surface
 * @version 3.0.9 Surface
 * @package \nabu\http\managers\base
 */
abstract class CNabuEmailingConnectorAdapter implements INabuEmailingConnector
{
    protected $protocol = self::SMTP_PROTOCOL_SMTP;
    protected $priority;
    protected $htmlCharset;
    protected $textCharset;
    protected $headCharset;

    protected $smtp_host;
    protected $smtp_port;
    protected $smtp_encryption;
    protected $smtp_auth;
    protected $smtp_username;
    protected $smtp_password;

    protected $error = false;

    public function __construct($protocol) {

        $this->protocol = $protocol;
    }

    public function getProtocol() {

        return $this->protocol;
    }

    public function setSMTPParameters($host, $port, $encryption, $auth = false, $username = false, $password = false) {

        $this->smtp_host = $host;
        $this->smtp_port = $port;
        $this->smtp_encryption = $encryption;
        $this->smtp_auth = $auth;
        $this->smtp_username = $username;
        $this->smtp_password = $password;
    }

    public function getPriority() {
        return $this->priority;
    }

    public function setPriority($priority) {
        $this->priority = $priority;
    }

    public function getHTMLCharset() {
        return $this->htmlCharset;
    }

    public function setHTMLCharset($encoding) {
        $this->htmlCharset = $encoding;
    }

    public function getTextCharset() {
        return $this->textCharset;
    }

    public function setTextCharset($encoding) {
        $this->textCharset = $encoding;
    }

    public function getHeadCharset() {
        return $this->headCharset;
    }

    public function setHeadCharset($encoding) {
        $this->headCharset = $encoding;
    }

    public function getError() {
        return $this->error;
    }

    public function setError($error) {
        $this->error = $error;
    }
}
