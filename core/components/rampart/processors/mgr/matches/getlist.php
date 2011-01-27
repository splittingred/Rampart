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
 * Get a list of ban matches
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
$sortAlias = $modx->getOption('sortAlias',$scriptProperties,'rptBanMatch');
$dir = $modx->getOption('dir',$scriptProperties,'DESC');
$search = $modx->getOption('search',$scriptProperties,false);

/* build query */
$c = $modx->newQuery('rptBanMatch');
$c->leftJoin('modResource','Resource');
if (!empty($search)) {
    $c->where(array(
        'OR:ip:LIKE' => '%'.$search.'%',
        'OR:hostname:LIKE' => '%'.$search.'%',
        'OR:email:LIKE' => '%'.$search.'%',
        'OR:username:LIKE' => '%'.$search.'%',
        'OR:useragent:LIKE' => '%'.$search.'%',
    ));
}
$count = $modx->getCount('rptBanMatch',$c);
$c->select($modx->getSelectColumns('rptBanMatch','rptBanMatch'));
$c->select($modx->getSelectColumns('modResource','Resource','',array('pagetitle')));
$c->sortby($sortAlias.'.'.$sort,$dir);
if ($isCombo || $isLimit) {
    $c->limit($limit,$start);
}
$matches = $modx->getCollection('rptBanMatch', $c);


$list = array();
foreach ($matches as $match) {
    $matchArray = $match->toArray();
    $matchArray['createdon'] = strftime('%b %d, %Y %I:%M %p',strtotime($match->get('createdon')));
    $matchArray['pagetitle'] = !empty($matchArray['pagetitle']) ? $matchArray['pagetitle'].' ('.$matchArray['resource'].')' : '';
    
    $list[]= $matchArray;
}
return $this->outputArray($list,$count);