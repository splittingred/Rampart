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
 * Approve multiple flagged users
 *
 * @package rampart
 * @subpackage processors
 */
if (empty($scriptProperties['users'])) {
    return $modx->error->failure($modx->lexicon('rampart.flagged_err_ns'));
}

$userIds = explode(',',$scriptProperties['users']);

foreach ($userIds as $userId) {
    $user = $modx->getObject('modUser',$userId);
    if (empty($user)) continue;
    $flaggedUser = $modx->getObject('rptFlaggedUser',array('username' => $user->get('username')));
    if (empty($flaggedUser)) continue;


    if (!$flaggedUser->sendActivationEmail($modx->rampart)) {
        $modx->log(modX::LOG_LEVEL_ERROR,'[Rampart] Could not send activation email for: '.$user->get('username'));
        continue;
    }

    $user->set('active',true);
    $flaggedUser->set('status','approved');
    $flaggedUser->set('actedon',time());
    $flaggedUser->set('actedby',$modx->user->get('id'));

    $user->save();
    $flaggedUser->save();
}

return $modx->error->success();
