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
 * Get a list of bans
 *
 * @package rampart
 * @subpackage processors
 */

/* set default properties */
$isLimit = !empty($scriptProperties['limit']);
$isCombo = !empty($scriptProperties['combo']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,20);
$sort = $modx->getOption('sort',$scriptProperties,'createdon');
$dir = $modx->getOption('dir',$scriptProperties,'DESC');
$search = $modx->getOption('search',$scriptProperties,false);

/* build query */
$c = $modx->newQuery('rptBan');
if (!empty($search)) {
    $c->where(array(
        'OR:reason:LIKE' => '%'.$search.'%',
        'OR:hostname:LIKE' => '%'.$search.'%',
        'OR:email:LIKE' => '%'.$search.'%',
        'OR:username:LIKE' => '%'.$search.'%',
    ));
}

$count = $modx->getCount('rptBan',$c);
$c->sortby($sort,$dir);
if ($isCombo || $isLimit) {
    $c->limit($limit,$start);
}
$bans = $modx->getCollection('rptBan', $c);


$list = array();
foreach ($bans as $ban) {
    $banArray = $ban->toArray();
    $banArray['ip'] = $ban->get('ip');
    $banArray['active'] = (boolean)$ban->get('active');

    $list[]= $banArray;
}
return $this->outputArray($list,$count);