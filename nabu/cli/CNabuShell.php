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

namespace nabu\cli;

use nabu\core\CNabuObject;

/**
 * Encapsulate the access to the system shell or command line
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\cli
 */
class CNabuShell extends CNabuObject
{
    private $verbose;
    private $muted;
    
    public function __construct()
    {
        $this->verbose = false;
        $this->muted = false;
    }
    
    public function isVerbose()
    {
        return $this->verbose;
    }
    
    public function setVerbose($verbose)
    {
        $this->verbose = $verbose;
    }
    
    public function isMuted()
    {
        return $this->muted;
    }
    
    public function setMuted($muted)
    {
        $this->muted = $muted;
    }
    
    public function exec($command, $params = null, &$response = false)
    {
        $line = $command;
        
        if (count($params) > 0) {
            foreach ($params as $key => $value) {
                $line .= ($key !== null ? ' '.$key : '').($value !== null ? ' '.$value : '');
            }
        }
        
        $retval = 0;
        exec("export PATH=".getenv('PATH')."; $line".($this->muted ? " > /dev/null" : " 2>&1"), $response, $retval);
        
        if ($this->verbose) {
            echo $line."\n";
            if (count($response) > 0) {
                foreach ($response as $line) {
                    if ($this->verbose) {
                        echo $line."\n";
                    }
                }
            }
        }
        

        if ($retval !== 0 && !$this->verbose && !$this->muted) {
            echo $line."\n";
            if (count($response) > 0) {
                foreach ($response as $line) {
                    echo $line."\n";
                }
            }
            echo ("---> Call to $line returns $retval error");
        }

        return ($retval === 0);
    }
}
