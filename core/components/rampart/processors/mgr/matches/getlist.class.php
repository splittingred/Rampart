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

class RampartMatchesGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'rptBanMatch';
    public $objectType = 'rampart.match';
    public $defaultSortField = 'createdon';
    public $defaultSortDirection = 'DESC';
    public $languageTopics = array('rampart:default');
    public function initialize() {
        $initialized = parent::initialize();
        $this->setDefaultProperties(array(
            'search' => false,
            'ban' => false,
        ));
        return $initialized;
    }

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c->leftJoin('modResource','Resource');
        $ban = $this->getProperty('ban');
        if (!empty($ban)) {
            $c->where(array(
                'rptBanMatch.ban' => $ban,
            ));
        }
        $search = $this->getProperty('search');
        if (!empty($search)) {
            $c->where(array(
                'ip:LIKE' => '%'.$search.'%',
                'OR:hostname:LIKE' => '%'.$search.'%',
                'OR:email:LIKE' => '%'.$search.'%',
                'OR:username:LIKE' => '%'.$search.'%',
                'OR:useragent:LIKE' => '%'.$search.'%',
            ),null,2);
        }
        return $c;
    }

    public function prepareQueryAfterCount(xPDOQuery $c) {
        $c->select($this->modx->getSelectColumns('rptBanMatch','rptBanMatch'));
        $c->select($this->modx->getSelectColumns('modResource','Resource','',array('pagetitle')));
        return $c;
    }

    protected function getArrayAsList($array = array()) {
        if (empty($array)) return '';
        $out = '<ul>'."\n";
        foreach ($array as $key => $elem) {
            $out .= '<li>';
            if (is_array($elem)) {
                $out .= $this->getArrayAsList($elem);
            } else {
                $out .= '<b>'.$key.'</b>: '.$elem;
            }
            $out .= '</li>'."\n";
        }
        $out .= '</ul>'."\n";
        return $out;
    }

    public function prepareRow(xPDOObject $object) {
        $objectArray = $object->toArray();
        $objectArray['createdon'] = strftime('%b %d, %Y %I:%M %p',strtotime($object->get('createdon')));
        $objectArray['pagetitle'] = !empty($objectArray['pagetitle']) ? $objectArray['pagetitle'].' ('.$objectArray['resource'].')' : '';

        if (!empty($objectArray['data'])) {
            $objectArray['data_formatted'] = $this->getArrayAsList($objectArray['data']);
        }
        return $objectArray;
    }
}
return 'RampartMatchesGetListProcessor';