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

namespace nabu\core\utils;

use \nabu\core\CNabuObject;

/**
 * Class to manage and transform images.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\utils
 */
class CNabuImageCanvas extends CNabuObject
{
    const RENDER_UNKNOWN = 0;
    const RENDER_JPEG = 1;
    const RENDER_PNG = 2;

    private $mimetype = false;
    private $imgtype = false;
    private $width = 0;
    private $height = 0;
    private $bits = 0;
    private $channels = 0;
    private $info = null;
    private $source = null;

    private $handler = null;
    private $render_type = self::RENDER_UNKNOWN;

    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
        $this->release();
    }

    public function getMIMEType()
    {
        return $this->mimetype;
    }

    public function setMIMEType($mimetype)
    {
        $this->mimetype = $mimetype;
    }

    public function getRenderType()
    {
        return $this->render_type;
    }

    public function setRenderType($type)
    {
        $this->render_type = $type;
    }

    public function getSize()
    {
        return array('w' => $this->width, 'h' => $this->height);
    }

    public function openFile($filename)
    {
        if (strlen($filename) > 0 && file_exists($filename)) {
            if ($this->handler !== null) {
                $this->release();
            }

            $this->source = $filename;
            $mime = getimagesize($this->source, $this->info);
            $this->width = $mime[0];
            $this->height = $mime[1];
            $this->imgtype = $mime[2];
            $this->mimetype = $mime['mime'];
            $this->bits = $mime['bits'];
            $this->channels = (array_key_exists('channels', $mime) ? $mime['channels'] : false);

            $this->handler = false;


            if ($mime['mime'] === 'image/png') {
                $this->handler = imagecreatefrompng($this->source);
                $this->render_type = self::RENDER_PNG;
            }
            if ($mime['mime'] === 'image/jpg') {
                $this->handler = imagecreatefromjpeg($this->source);
                $this->render_type = self::RENDER_JPEG;
            }
            if ($mime['mime'] === 'image/jpeg') {
                $this->handler = imagecreatefromjpeg($this->source);
                $this->render_type = self::RENDER_JPEG;
            }
            if ($mime['mime'] === 'image/pjpeg') {
                $this->handler = imagecreatefromjpeg($this->source);
                $this->render_type = self::RENDER_JPEG;
            }
        }
    }

    public function saveFile($filename)
    {
        switch ($this->render_type) {
            case self::RENDER_JPEG:
                imagejpeg($this->handler, $filename, 80);
                break;
            case self::RENDER_PNG:
                imagepng($this->handler, $filename);
                break;
        }
    }

    public function scale($width, $height)
    {
        if ($this->handler && ($width || $height)) {
            $aso = $this->height / $this->width;

            if (!($width !== false && $height !== false)) {
                if (!$width) {
                    $width = round($height / $aso);
                }
                if (!$height) {
                    $height = round($width * $aso);
                }
            }

            if ($this->render_type === self::RENDER_PNG) {
                $new_img = imagecreatetruecolor($width, $height);
                imagealphablending($new_img, false);
                imagesavealpha(($new_img), true);
                $trans = imagecolorallocatealpha($new_img, 255, 255, 255, 127);
                imagefilledrectangle($new_img, 0, 0, $width, $height, $trans);
            } else {
                $new_img = imagecreatetruecolor($width, $height);
            }
            imagecopyresampled(
                $new_img,
                $this->handler,
                0,
                0,
                0,
                0,
                $width,
                $height,
                $this->width,
                $this->height
            );
            imagedestroy($this->handler);
            $this->handler = $new_img;
            $this->width = $width;
            $this->height = $height;
        }
    }

    public function crop($width, $height)
    {
        if ($this->handler && $width && $height) {
            $aso = $this->height / $this->width;
            $ast = $height / $width;

            if ($aso < $ast) {
                $nw = round($this->height / $ast);
                $dw = round(($this->width - $nw) / 2);
                $nh = $this->height;
                $dh = 0;
                $na = $nh / $nw;
            } else {
                $nw = $this->width;
                $dw = 0;
                $nh = round($this->width * $ast);
                $dh = round(($this->height - $nh) / 2);
                $na = $nh / $nw;
            }

            if ($this->render_type === self::RENDER_PNG) {
                $new_img = imagecreatetruecolor($nw, $nh);
                imagealphablending($new_img, false);
                imagesavealpha(($new_img), true);
                $trans = imagecolorallocatealpha($new_img, 255, 255, 255, 127);
                imagefilledrectangle($new_img, 0, 0, $width, $height, $trans);
            } else {
                $new_img = imagecreatetruecolor($nw, $nh);
            }
            imagecopy($new_img, $this->handler, 0, 0, $dw, $dh, $nw, $nh);
            imagedestroy($this->handler);
            $this->handler = $new_img;
            $this->width = $nw;
            $this->height = $nh;
            $this->scale($width, $height);
        }
    }

    public function output()
    {
        if ($this->handler) {
            switch ($this->render_type) {
                case self::RENDER_JPEG:
                    imagejpeg($this->handler, null, 80);
                    break;
                case self::RENDER_PNG:
                    imagepng($this->handler);
                    break;
            }
        } elseif (file_exists($this->source)) {
            echo file_get_contents($this->source);
        }
    }

    public function release()
    {
        if ($this->handler) {
            imagedestroy($this->handler);
            $this->handler = null;
            $this->render_type = self::RENDER_UNKNOWN;
            $this->mimetype = false;
            $this->imgtype = false;
            $this->width = 0;
            $this->height = 0;
            $this->bits = 0;
            $this->channels = 0;
            $this->info = null;
            $this->source = null;
        }
    }
}
