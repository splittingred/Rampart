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
 * Get a list of flagged users
 *
 * @package rampart
 * @subpackage processors
 */

/* set default properties */
$isLimit = !empty($scriptProperties['limit']);
$isCombo = !empty($scriptProperties['combo']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,20);
$sort = $modx->getOption('sort',$scriptProperties,'username');
$dir = $modx->getOption('dir',$scriptProperties,'DESC');
$search = $modx->getOption('search',$scriptProperties,false);
$status = $modx->getOption('status',$scriptProperties,'');

/* build query */
$c = $modx->newQuery('modUser');
$c->innerJoin('rptFlaggedUser','Flag','Flag.username = modUser.username');
$c->innerJoin('modUserProfile','Profile');
if (!empty($search)) {
    $c->where(array(
        'modUser.username:LIKE' => '%'.$search.'%',
        'OR:Profile.email:LIKE' => '%'.$search.'%',
        'OR:Profile.fullname:LIKE' => '%'.$search.'%',
    ),null,null,2);
}
$c->where(array(
    'Flag.status' => $status,
));

$count = $modx->getCount('modUser',$c);
$c->select($modx->getSelectColumns('modUser','modUser'));
$c->select($modx->getSelectColumns('modUserProfile','Profile','',array(
    'email','fullname',
)));
$c->select($modx->getSelectColumns('rptFlaggedUser','Flag','',array(
    'ip','hostname','useragent','flaggedfor','flaggedon','approved',
)));
$c->sortby($sort,$dir);
if ($isCombo || $isLimit) {
    $c->limit($limit,$start);
}
$users = $modx->getCollection('modUser', $c);


$list = array();
foreach ($users as $user) {
    $userArray = $user->toArray();
    $userArray['active'] = (boolean)$user->get('active');
    $userArray['flaggedon'] = strftime('%b %d, %Y %I:%M %p',strtotime($user->get('flaggedon')));
    $userArray['flaggedfor'] = $modx->lexicon('rampart.flag_'.$user->get('flaggedfor'));

    $list[]= $userArray;
}
return $this->outputArray($list,$count);