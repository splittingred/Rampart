<?php
/**
 * Rampart
 *
 * Copyright 2011 by Shaun McCormick <shaun@modx.com>
 *
 * Rampart is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * Rampart is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Rampart; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package rampart
 */
/**
 * Handles all plugin events for Rampart
 * 
 * @package rampart
 */
$modelPath = $modx->getOption('rampart.core_path',null,$modx->getOption('core_path').'components/rampart/').'model/';
$rampart = $modx->getService('rampart','Rampart',$modelPath.'rampart/');

switch ($modx->event->name) {
    case 'OnWebPageInit':
        if ($modx->getOption('rampart.honeypot.enabled',null,false)) {
            /* handle ProjectHoneyPot DNS blacklist integration */
            if ($modx->loadClass('projecthoneypot.RampartHoneyPot',$rampart->config['modelPath'],true,true)) {
                $honey = new RampartHoneyPot($rampart);
                if (!$honey->check()) {
                    $info = array(
                        Rampart::IP => $_SERVER['REMOTE_ADDR'],
                    );
                    if (!$rampart->checkWhiteList($info)) {
                        $honey->prevent();
                    }
                }
            } else {
                $modx->log(modX::LOG_LEVEL_ERROR,'[Rampart] Could not load RampartHoneyPot class from: '.$rampart->config['modelPath']);
            }
        }
        break;
}
return;