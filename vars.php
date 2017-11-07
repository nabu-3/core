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

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package vars
 */

/**
 * Global array that contains string representation of each kind of message error levels
 */
$NABU_MESSAGE_TYPE_NAME = array(
    /* 0x0001 */ E_ERROR => 'ERROR',
    /* 0x0002 */ E_WARNING => 'WARNING',
    /* 0x0004 */ E_PARSE => 'PARSE',
    /* 0x0008 */ E_NOTICE => 'NOTICE',
    /* 0x0010 */ E_CORE_ERROR => 'CORE ERROR',
    /* 0x0020 */ E_CORE_WARNING => 'CORE WARNING',
    /* 0x0040 */ E_COMPILE_ERROR => 'COMPILE ERROR',
    /* 0x0080 */ E_COMPILE_WARNING => 'COMPILE WARNING',
    /* 0x0100 */ E_USER_ERROR => 'USER ERROR',
    /* 0x0200 */ E_USER_WARNING => 'USER WARNING',
    /* 0x0400 */ E_USER_NOTICE => 'USER NOTICE',
    /* 0x0800 */ E_STRICT => 'STRICT',
    /* 0x1000 */ E_RECOVERABLE_ERROR => 'RECOVERABLE ERROR',
    /* 0x2000 */ E_DEPRECATED => 'DEPRECATED',
    /* 0x4000 */ E_USER_DEPRECATED => 'USER DEPRECATED'
);

/**
 * Global array of HTTP Response Codes and their equivalent messages to be placed in
 * HTTP responses.
 */
$NABU_HTTP_CODES = array(
    100 => 'Continue',
    101 => 'Switching Protocols',
    102 => 'Processing',
    200 => 'OK',
    201 => 'Created',
    202 => 'Accepted',
    203 => 'Non-Authoritative Information',
    204 => 'No Content',
    205 => 'Reset Content',
    206 => 'Partial Content',
    207 => 'Multi-Status',
    208 => 'Already Reported',
    226 => 'IM Used',
    300 => 'Multiple Choices',
    301 => 'Moved Permanently',
    302 => 'Found',
    303 => 'See Other',
    304 => 'Not Modified',
    305 => 'Use Proxy',
    306 => 'Switch Proxy',
    307 => 'Temporary Redirect',
    308 => 'Permanent Redirect',
    400 => 'Bad Request',
    401 => 'Unauthorized',
    402 => 'Payment Required',
    403 => 'Forbidden',
    404 => 'Not Found',
    405 => 'Method Not Allowed',
    406 => 'Not Acceptable',
    407 => 'Proxy Authentication Required',
    408 => 'Request Timeout',
    409 => 'Conflict',
    410 => 'Gone',
    411 => 'Lenght Required',
    412 => 'Precondition Failed',
    413 => 'Request Entity Too Large',
    414 => 'Request-URI Too Long',
    415 => 'Unsupported Media Type',
    416 => 'Requested Range Not Satisfiable',
    417 => 'Expectation Failed',
    418 => 'I\'m a teapot',
    419 => 'Authentication Timeout',
    420 => 'Enhance Your Calm',
    422 => 'Unprocessable Entity',
    423 => 'Locked',
    424 => 'Failed Dependency',
    425 => 'Unordered Collection',
    426 => 'Upgrade Required',
    428 => 'Precondition Required',
    429 => 'Too Many Requests',
    431 => 'Request Header Fields Too Large',
    440 => 'Login Timeout',
    444 => 'Not Response',
    449 => 'Retry With',
    450 => 'Blocked By Windows Parental Controls',
    451 => 'Unavailable For Legal Reasons',
    494 => 'Request Header Too Large',
    495 => 'Cert Error',
    496 => 'No Cert',
    497 => 'HTTP to HTTPS',
    499 => 'Client Closed Request',
    500 => 'Internal Server Error',
    501 => 'Not Implemented',
    502 => 'Bad Gateway',
    503 => 'Service Unavailable',
    504 => 'Gateway Timeout',
    505 => 'HTTP Version Not Supported',
    506 => 'Variant Also Negotiates',
    507 => 'Insufficient Storage',
    508 => 'Loop Detected',
    509 => 'Bandwidth Limit Exceeded',
    510 => 'Not Extended',
    511 => 'Network Authentication Required',
    520 => 'Origin Error',
    522 => 'Connection Timed out',
    523 => 'Proxy Declined Request',
    524 => 'A timeout occurred',
    598 => 'Network read timeout error',
    599 => 'Network connect timeout error'
);
