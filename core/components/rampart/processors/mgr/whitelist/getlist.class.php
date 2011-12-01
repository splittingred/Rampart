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
 * Get a list of whitelists
 *
 * @package rampart
 * @subpackage processors
 */
class RampartWhiteListGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'rptWhiteList';
    public $objectType = 'rampart.whitelist';
    public $defaultSortField = 'createdon';
    public $defaultSortDirection = 'DESC';
    public $languageTopics = array('rampart:default');
    public function initialize() {
        $initialized = parent::initialize();
        $this->setDefaultProperties(array(
            'search' => false,
        ));
        return $initialized;
    }

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $search = $this->getProperty('search');
        if (!empty($search)) {
            $c->where(array(
                'ip:LIKE' => '%'.$search.'%',
                'OR:notes:LIKE' => '%'.$search.'%',
            ));
        }
        return $c;
    }

    public function prepareRow(xPDOObject $object) {
        $objectArray = $object->toArray();
        $objectArray['active'] = (boolean)$object->get('active');
        $objectArray['createdon'] = strftime('%b %d, %Y %I:%M %p',strtotime($object->get('createdon')));
        return $objectArray;
    }
}
return 'RampartWhiteListGetListProcessor';