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
 * Adds modActions and modMenus into package
 *
 * @var modX $modx
 * @package rampart
 * @subpackage build
 */
/** @var modAction $action */
$action= $modx->newObject('modAction');
$action->fromArray(array(
    'id' => 1,
    'namespace' => 'rampart',
    'parent' => 0,
    'controller' => 'controllers/index',
    'haslayout' => 1,
    'lang_topics' => 'rampart:default',
    'assets' => '',
),'',true,true);

/* load menu into action */
/** @var modMenu $menu */
$menu= $modx->newObject('modMenu');
$menu->fromArray(array(
    'text' => 'rampart',
    'parent' => 'components',
    'description' => 'rampart.menu_desc',
    'icon' => 'images/icons/plugin.gif',
    'menuindex' => 0,
    'params' => '',
    'handler' => '',
),'',true,true);
$menu->addOne($action);

return $menu;