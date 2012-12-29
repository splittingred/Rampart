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
/* @var modX $modx
 * @var Rampart $rampart
 */
$modelPath = $modx->getOption('rampart.core_path',null,$modx->getOption('core_path').'components/rampart/').'model/';
$rampart = $modx->getService('rampart','Rampart',$modelPath.'rampart/');

switch ($modx->event->name) {
    case 'OnWebPageInit':
        if ($modx->getOption('rampart.honeypot.enabled',null,false) && $modx->getOption('rampart.honeypot.fullwall_enabled',null,false)) {
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

        if ($modx->getOption('rampart.denyaccess', null, false)) {
            $ip = $_SERVER['REMOTE_ADDR'];
            $result = array(
                Rampart::STATUS => Rampart::STATUS_OK,
                Rampart::REASON => '',
                Rampart::IP => $ip,
                Rampart::HOSTNAME => gethostbyaddr($ip),
                Rampart::EMAIL => '',
                Rampart::USERNAME => '',
                Rampart::USER_AGENT => $_SERVER['HTTP_USER_AGENT'],
            );
            if (!$rampart->checkWhiteList($result)) {
                $result = $rampart->checkBanList($result);
            }
            if ($result[Rampart::STATUS] == Rampart::STATUS_BANNED) {
                $threshold = $modx->getOption('rampart.denyaccess.threshold', null, 5);
                $banCount = $modx->getCount('rptBanMatch', array('ban' => $result[Rampart::BAN]));
                if ((($threshold > 1) && ($banCount >= $threshold)) || 'Manual Ban Match' == $result[Rampart::REASON]) {
                    @session_write_close();
                    header('HTTP/1.1 403 Forbidden');
                    $message = '<p>Sorry, you have been banned. If you feel this is in error, please contact the administrator of this site.</p>';
                    echo "<html>\n<head>\n<title>Access Denied</title>\n</head>\n<body>\n" . $message . "\n</body>\n</html>";
                    exit();
                }
            }
        }

        break;
}
return;
