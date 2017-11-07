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

namespace nabu\visual\site;
use mxGeometry;
use mxPoint;
use mxCell;
use nabu\visual\site\base\CNabuSiteVisualEditorItemBase;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\visual\site
 */
class CNabuSiteVisualEditorItem extends CNabuSiteVisualEditorItemBase
{
    public function applyGeometryToEdge(mxCell $edge)
    {
        $points = $this->getValueJSONDecoded('nb_site_visual_editor_item_points');

        if ($points !== null) {
            $geometry = $edge->getGeometry();
            if ($points['source'] !== null) {
                $point = new mxPoint();
                $point->x = $points['source']['x'];
                $point->y = $points['source']['y'];
                $geometry->sourcePoint = $point;
            }
            if ($points['target'] !== null) {
                $point = new mxPoint();
                $point->x = $points['target']['x'];
                $point->y = $points['target']['y'];
                $geometry->targetPoint = $point;
            }
            if (count($points['intermediate']) > 0) {
                $geometry->points = array();
                foreach ($points['intermediate'] as $ep) {
                    $point = new mxPoint();
                    $point->x = $ep['x'];
                    $point->y = $ep['y'];
                    $geometry->points[] = $point;
                }
            }
            $edge->setGeometry($geometry);
        }
    }
}
