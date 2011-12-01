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
 * @subpackage processors
 */
class RampartBanCreateProcessor extends modObjectCreateProcessor {
    public $classKey = 'rptBan';
    public $objectType = 'rampart.ban';
    public $languageTopics = array('rampart:default');

    public function beforeSave() {
        $this->object->set('createdon',strftime('%Y-%m-%d %H:%M:%S'));
        $this->object->set('createdby',$this->modx->user->get('id'));
        return parent::beforeSave();
    }
}
return 'RampartBanCreateProcessor';