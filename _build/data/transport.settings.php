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
 * @package rampart
 * @subpackage build
 */
$settings = array();

/* StopForumSpam */
$settings['rampart.sfs_ipban_threshold']= $modx->newObject('modSystemSetting');
$settings['rampart.sfs_ipban_threshold']->fromArray(array(
    'key' => 'rampart.sfs_ipban_threshold',
    'value' => 25,
    'xtype' => 'textfield',
    'namespace' => 'rampart',
    'area' => 'StopForumSpam',
),'',true,true);
$settings['rampart.sfs_ipban_expiration']= $modx->newObject('modSystemSetting');
$settings['rampart.sfs_ipban_expiration']->fromArray(array(
    'key' => 'rampart.sfs_ipban_expiration',
    'value' => 30,
    'xtype' => 'textfield',
    'namespace' => 'rampart',
    'area' => 'StopForumSpam',
),'',true,true);

/* HoneyPot */
$settings['rampart.honeypot.access_key']= $modx->newObject('modSystemSetting');
$settings['rampart.honeypot.access_key']->fromArray(array(
    'key' => 'rampart.honeypot.access_key',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => 'rampart',
    'area' => 'HoneyPot',
),'',true,true);
$settings['rampart.honeypot.ban_expiration']= $modx->newObject('modSystemSetting');
$settings['rampart.honeypot.ban_expiration']->fromArray(array(
    'key' => 'rampart.honeypot.ban_expiration',
    'value' => 30,
    'xtype' => 'textfield',
    'namespace' => 'rampart',
    'area' => 'HoneyPot',
),'',true,true);
$settings['rampart.honeypot.blacklist_message']= $modx->newObject('modSystemSetting');
$settings['rampart.honeypot.blacklist_message']->fromArray(array(
    'key' => 'rampart.honeypot.blacklist_message',
    'value' => 'Sorry, you have been blacklisted.',
    'xtype' => 'textfield',
    'namespace' => 'rampart',
    'area' => 'HoneyPot',
),'',true,true);
$settings['rampart.honeypot.blhost']= $modx->newObject('modSystemSetting');
$settings['rampart.honeypot.blhost']->fromArray(array(
    'key' => 'rampart.honeypot.blhost',
    'value' => 'dnsbl.httpbl.org',
    'xtype' => 'textfield',
    'namespace' => 'rampart',
    'area' => 'HoneyPot',
),'',true,true);
$settings['rampart.honeypot.enabled']= $modx->newObject('modSystemSetting');
$settings['rampart.honeypot.enabled']->fromArray(array(
    'key' => 'rampart.honeypot.enabled',
    'value' => false,
    'xtype' => 'combo-boolean',
    'namespace' => 'rampart',
    'area' => 'HoneyPot',
),'',true,true);

return $settings;