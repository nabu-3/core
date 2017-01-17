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

namespace nabu\http\renders;

use nabu\core\exceptions\ENabuCoreException;
use nabu\http\renders\base\CNabuHTTPResponseRenderAdapter;
use nabu\utils\CNabuImageCanvas;

/**
 * Class to dump images as HTTP response.
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \nabu\http\renders\base
 */
class CNabuHTTPResponseImageRender extends CNabuHTTPResponseRenderAdapter {

    private $output_type = CNabuImageCanvas::RENDER_UNKNOWN;
    private $image_path = false;
    private $cache_path = false;
    
    public function __construct($main_render = false) {
        
        parent::__construct($main_render);
    }

    public function getCachePath() {
        
        return $this->cache_path;
    }
    
    public function setCachePath($path) {
        
        $this->cache_path = $path;
    }
    
    public function getImagePath() {
        
        return $this->image_path;
    }
    
    public function setImagePath($path) {
        
        $this->image_path = $path;
    }
    
    public function mutateOutput($type) {
        
        $this->output_type = $type;
    }
    
    private function calculateSourceFilename() {
        
        if ($this->image_path) {
            if (!file_exists($this->image_path)) {
                throw new ENabuCoreException(
                    ENabuCoreException::ERROR_FOLDER_NOT_FOUND,
                    array($this->image_path)
                );
            } else {
                $filename = $this->image_path . $this->source_filename;
            }
        } else {
            $filename = $this->source_filename;
        }
        
        if (!file_exists($filename)) {
            throw new ENabuCoreException(
                ENabuCoreException::ERROR_FILE_NOT_FOUND,
                array($filename)
            );
        }
        
        return $filename;
    }
    
    private function calculateCacheFilename($width, $height, $method) {
        
        if ($this->cache_path) {

            if (!file_exists($this->cache_path)) {
                throw new ENabuCoreException(
                    ENabuCoreException::ERROR_FOLDER_NOT_FOUND,
                    array($this->cache_path)
                );
            } else {
                $filename = $this->cache_path . $this->source_filename;
            }
        }

        if ($width !== false && $height !== false && strlen($method) > 0) {
            
            if (isset($filename)) {
                $cache = $filename . '_' . $method . '_' . $width . '_' . $height;
                if (strlen($cache) !== strlen($filename)) {
                    return $cache;
                }
            }
        }
        
        return false;
    }
    
    private function normalizeSize($filename) {
        
        $width = ($this->hasValue('Width') ? (int)$this->getValue('Width') : false);
        $height = ($this->hasValue('Height') ? (int)$this->getValue('Height') : false);
        $method = ($this->hasValue('Method')
                ? $this->getValue('Method')
                : ($width !== false || $height !== false ? 'scale' : false));
        
        if ($width !== false || $height !== false) {
            switch ($method) {
                case 'crop':
                    $width = ($width === false ? $height : $width);
                    $height = ($height === false ? $width : $height);
                    break;
                case 'scale':
                    if ($width === false || $height === false) {
                        $sz = getimagesize($filename);
                        $aso = $sz[1] / $sz[0];

                        if (!($width !== false && $height !== false)) {
                            if (!$width) {
                                $width = round($height / $aso);
                            }
                            if (!$height) {
                                $height = round($width * $aso);
                            }
                        }
                    }
                    break;
                default:
                    $width = false;
                    $height = false;
                    $method = false;
            }
        }

        return array($width, $height, $method);
    }
    
    public function render()
    {
        $source_filename = $this->calculateSourceFilename();
        list($width, $height, $method) = $this->normalizeSize($source_filename);
        $cache_filename = $this->calculateCacheFilename($width, $height, $method);
        
        if ($this->dumpFile($cache_filename)) {
            return;
        }
        
        if ($cache_filename === false &&
            $width === false &&
            $height === false &&
            $this->dumpFile($source_filename)
        ) {
            return;
        }
        
        $image = new CNabuImageCanvas();

        $image->openFile($source_filename);
        if (isset($method)) {
            switch ($method) {
                case 'scale': {
                    $image->scale($width, $height);
                    break;
                }
                case 'crop': {
                    $image->crop($width, $height);
                    break;
                }
            }
        }
        if (isset($cache_filename)) {
            $image->saveFile($cache_filename);
        }

        $this->output_type = $image->getRenderType();
        if ($this->output_type !== CNabuImageCanvas::RENDER_UNKNOWN) {
            $image->setRenderType($this->output_type);
        }
        
        $image->output();
        $image->release();
    }
}