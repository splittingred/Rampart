<?php
/**
 * Rampart
 *
 * Copyright 2010-2011 by Shaun McCormick <shaun@modx.com>
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
 */
class rptBan extends xPDOSimpleObject {
    public function set($k, $v= null, $vType= '') {
        switch ($k) {
            case 'ip':
                $ipex = explode('.',$v);
                for ($i=0;$i<4;$i++) {
                    $n = $i+1;
                    if (!isset($ipex[$i])) {
                        $this->set('ip_low'.$n,0);
                        $this->set('ip_high'.$n,0);
                    } else if (strpos($ipex[$i],'-') !== false) {
                        $ipr = explode('-',$ipex[$i]);
                        $this->set('ip_low'.$n,$ipr[0]);
                        $this->set('ip_high'.$n,$ipr[1]);
                    } else if ($ipex[$i] == '*') {
                        $this->set('ip_low'.$n,0);
                        $this->set('ip_high'.$n,255);
                    } else {
                        $this->set('ip_low'.$n,$ipex[$i]);
                        $this->set('ip_high'.$n,$ipex[$i]);
                    }
                }
                break;
        }
        return parent :: set($k,$v,$vType);
    }

    public function get($k, $format = null, $formatTemplate= null) {
        switch ($k) {
            case 'ip':
                $ip = '';
                $i = 1;
                for ($i=1;$i<5;$i++) {
                    $ip .= '.';
                    $block = $this->get('ip_low'.$i) == $this->get('ip_high'.$i) ? $this->get('ip_low'.$i) : $this->get('ip_low'.$i).'-'.$this->get('ip_high'.$i);
                    if ($block == '0-255' && !empty($block)) $block = '*';
                    $ip .= $block;
                }
                $v = trim($ip,'.');
                if ($v == '0.0.0.0') $v = '';
                break;
            default:
                $v = parent::get($k,$format,$formatTemplate);
                break;
        }
        return $v;
    }
}