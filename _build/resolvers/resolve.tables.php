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
 * Resolves db table creation
 *
 * @package rampart
 * @subpackage build
 */
/* @var $object
 * @var modX $modx
 * @var array $options 
 */ 
if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            $modx =& $object->xpdo;
            $modelPath = $modx->getOption('rampart.core_path',null,$modx->getOption('core_path').'components/rampart/').'model/';
            $modx->addPackage('rampart',$modelPath);

            $m = $modx->getManager();
            $m->createObjectContainer('rptBan');
            $m->createObjectContainer('rptFlaggedUser');
            $m->createObjectContainer('rptBanMatch');
            $m->createObjectContainer('rptBanMatchField');
            $m->createObjectContainer('rptBanMatchBan');
            $m->createObjectContainer('rptWhiteList');
            break;
    }
}
return true;
