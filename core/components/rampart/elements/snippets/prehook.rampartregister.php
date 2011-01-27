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
 * preHook for Register snippet that utilizes Rampart
 * 
 * @package rampart
 */
$modelPath = $modx->getOption('rampart.core_path',null,$modx->getOption('core_path').'components/rampart/').'model/';
$rampart = $modx->getService('rampart','Rampart',$modelPath.'rampart/');

$username = $fields[$usernameField];
$email = $fields['email'];

$activationEmailTpl = $modx->getOption('activationEmailTpl',$scriptProperties,'');
$activationEmailSubject = $modx->getOption('activationEmailSubject',$scriptProperties,'');
$activationResourceId = $modx->getOption('activationResourceId',$scriptProperties,'');
$rptSpammerErrorMessage = $modx->getOption('rptSpammerErrorMessage',$scriptProperties,'Your account has been banned as a spammer. Sorry.');

$response = $rampart->check($username,$email);

$hook->setValue('ip',$response[Rampart::IP]);
$hook->setValue('hostname',$response[Rampart::HOSTNAME]);
$hook->setValue('userAgent',$response[Rampart::USER_AGENT]);

if ($response[Rampart::STATUS] == Rampart::STATUS_BANNED) {
    $hook->addError('username',$rptSpammerErrorMessage);
    return false;
}
if ($response[Rampart::STATUS] == Rampart::STATUS_MODERATED) {
    /* prevents confirmation email from being sent */
    $hook->setValue('register.moderate',true);

    $password = $rampart->encrypt($fields['password']);

    /* create a flagged user record */
    $flu = $modx->newObject('rptFlaggedUser');
    $flu->set('username',$response[Rampart::USERNAME]);
    $flu->set('password',$password);
    $flu->set('ip',$response[Rampart::IP]);
    $flu->set('hostname',$response[Rampart::HOSTNAME]);
    $flu->set('useragent',$response[Rampart::USER_AGENT]);
    $flu->set('flaggedfor',$response[Rampart::REASON]);
    $flu->set('activation_email_tpl',$activationEmailTpl);
    $flu->set('activation_email_subject',$activationEmailSubject);
    $flu->set('activation_resource_id',$activationResourceId);
    $flu->set('flaggedon',time());
    $flu->save();
    return true;
}

return true;