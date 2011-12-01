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
 * preHook for Quip snippet that utilizes Rampart, simple ban-checking only
 *
 * @var modX $modx
 * @var Rampart $rampart
 * @var array $scriptProperties
 * @var quipHooks $hook
 * @var array $fields
 * @package rampart
 */
$modelPath = $modx->getOption('rampart.core_path',null,$modx->getOption('core_path').'components/rampart/').'model/';
$rampart = $modx->getService('rampart','Rampart',$modelPath.'rampart/');

$email = $fields['email'];

$rptSpammerErrorMessage = $modx->getOption('rptSpammerErrorMessage',$scriptProperties,'Your account has been banned as a spammer. Sorry.');

$response = $rampart->check('',$email);

$hook->setValue('ip',$response[Rampart::IP]);
$hook->setValue('hostname',$response[Rampart::HOSTNAME]);
$hook->setValue('userAgent',$response[Rampart::USER_AGENT]);

if ($response[Rampart::STATUS] == Rampart::STATUS_BANNED) {
    $hook->addError('email',$rptSpammerErrorMessage);
    return false;
}

return true;