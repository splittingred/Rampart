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
class RampartFlaggedGetListProcessor extends modObjectGetListProcessor {

    public $classKey = 'modUser';
    public $objectType = 'rampart.flag';
    public $defaultSortField = 'username';
    public $defaultSortDirection = 'DESC';
    public $languageTopics = array('rampart:default');
    public function initialize() {
        $initialized = parent::initialize();
        $this->setDefaultProperties(array(
            'search' => false,
            'status' => '',
        ));
        return $initialized;
    }

    public function prepareQueryBeforeCount(xPDOQuery $c) {
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
            'Flag.status' => $this->getProperty('status'),
        ));
        return $c;
    }

    public function prepareQueryAfterCount(xPDOQuery $c) {
        $c->select($this->modx->getSelectColumns('modUser','modUser'));
        $c->select($this->modx->getSelectColumns('modUserProfile','Profile','',array(
            'email','fullname',
        )));
        $c->select($this->modx->getSelectColumns('rptFlaggedUser','Flag','',array(
            'ip','hostname','useragent','flaggedfor','flaggedon','approved',
        )));
        return $c;
    }

    public function prepareRow(xPDOObject $object) {
        $objectArray = $object->toArray();
        $objectArray['active'] = (boolean)$object->get('active');
        $objectArray['flaggedon'] = strftime('%b %d, %Y %I:%M %p',strtotime($object->get('flaggedon')));
        $objectArray['flaggedfor'] = $this->modx->lexicon('rampart.flag_'.$object->get('flaggedfor'));
        return $objectArray;
    }
}
return 'RampartFlaggedGetListProcessor';