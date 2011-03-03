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
 * @subpackage build
 */
function getSnippetContent($filename) {
    $o = file_get_contents($filename);
    $o = str_replace('<?php','',$o);
    $o = str_replace('?>','',$o);
    $o = trim($o);
    return $o;
}
$snippets = array();

$snippets[0]= $modx->newObject('modSnippet');
$snippets[0]->fromArray(array(
    'id' => 0,
    'name' => 'preHook.RampartRegister',
    'description' => 'preHook for Rampart Integration into Register.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/prehook.rampartregister.php'),
    'properties' => '',
),'',true,true);

$snippets[1]= $modx->newObject('modSnippet');
$snippets[1]->fromArray(array(
    'id' => 1,
    'name' => 'hook.RampartFormIt',
    'description' => 'preHook for Rampart Integration into FormIt.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/hook.rampartformit.php'),
    'properties' => '',
),'',true,true);

$snippets[2]= $modx->newObject('modSnippet');
$snippets[2]->fromArray(array(
    'id' => 2,
    'name' => 'hook.RampartQuip',
    'description' => 'preHook for Rampart Integration into Quip.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/prehook.rampartquip.php'),
    'properties' => '',
),'',true,true);

return $snippets;