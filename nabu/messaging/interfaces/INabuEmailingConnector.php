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

namespace nabu\messaging\interfaces;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.9 Surface
 * @version 3.0.9 Surface
 * @package \nabu\http\managers\base
 */
interface INabuEmailingConnector {

    const SMTP_PROTOCOL_SMTP = 0x0100;

    const SMTP_NOT_ENCRYPTED = 0x0200;
    const SMTP_SSL_ENCRYPTION = 0x0201;
    const SMTP_TLS_ENCRYPTION = 0x0202;

    public function getProtocol();

    public function setSMTPParameters($host, $port, $encryption, $auth = false, $username = false, $password = false);

    public function getPriority();
    public function setPriority($priority);

    public function getHTMLCharset();
    public function setHTMLCharset($encoding);

    public function getTextCharset();
    public function setTextCharset($encoding);

    public function getHeadCharset();
    public function setHeadCharset($encoding);

    public function getError();
    public function setError($error);

    public function ping();
    public function send($from, $to, $cc, $subject, $text, $html, $attachments = null, $cco = null, $reply_to = null);

    public function close();
}
