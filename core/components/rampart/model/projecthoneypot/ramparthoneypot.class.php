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
 * ProjectHoneyPot integration into Rampart
 *
 * @package rampart
 */
if (!class_exists('RampartHoneyPot')) {
class RampartHoneyPot {
    public $values = array();

    function __construct(Rampart &$rampart, array $config = array()) {
        $this->rampart =& $rampart;
        $this->modx =& $rampart->modx;

        $this->config = array_merge(array(
            'access_key' => $this->modx->getOption('rampart.honeypot.access_key',null,''),
            'host' => $this->modx->getOption('rampart.honeypot.blhost',null,'dnsbl.httpbl.org'),
        ),$config);
    }

    /**
     * Check against ban list
     *
     * @return bool True if passed checks, false otherwise
     */
    public function check() {
        $passed = true;
        $ip = $this->_getIp();
        $this->dnsLookup($ip);
        if (empty($this->values)) return $passed;
        
        $expires = $this->modx->getOption('rampart.honeypot.ban_expiration',$this->config,30); /* # of days to ban */
        if (!empty($this->values['comment_spammer'])) {
            $this->rampart->addBan($ip,'HoneyPot: Comment Spammer',$expires,$this->values['last_activity_time'],$this->values);
            $passed = false;
        }
        if (!empty($this->values['harvester'])) {
            $this->rampart->addBan($ip,'HoneyPot: Harvester',$expires,$this->values['last_activity_time'],$this->values);
            $passed = false;
        }
        return $passed;
    }

    /**
     * Lookup user against HoneyPot DNS Blacklist
     * 
     * @param string $ip The IP to check against
     * @return array|bool Either false if no return value, or an array of data about the client
     */
    public function dnsLookup($ip) {
        if (empty($this->config['access_key'])) return false;
        $ip = $this->_reverseIp($ip);
        if (empty($ip)) return false;

        $query = $this->config['access_key'].'.'.$ip.'.'.$this->config['host'];
        $response = gethostbyname($query);
        
        /* if response is query, then user is not in blacklist */
        if ($response == $query) {
            return true;
        }
        $values = array();
        $values['response'] = $response;
        $response = explode('.', $response);
        
        if ($response[0] != '127') {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[Rampart] Could not connect to Project Honey Pot to check IP '.$ip.' - response: '.print_r($response,true));
            return false;
        }

        $values['last_activity'] = $response[1];

        $date = new DateTime();
        $date->sub(new DateInterval('P'.$values['last_activity'].'D'));
        $values['last_activity_time'] = $date->format('Y-m-d h:i:s');
        $values['threat'] = $response[2];
        $values['type'] = $response[3];
        if ($response[3] == 0) {
            $values['search_engine'] = true;
        }
        if ($response[3] & 1) {
            $values['suspicious'] = true;
        }
        if ($response[3] & 2) {
            $values['harvester'] = true;
        }
        if ($response[3] & 4) {
            $values['comment_spammer'] = true;
        }

        $this->values = $values;
        return $values;
    }

    /**
     * Prevent access into site.
     */
    public function prevent() {
        $message = '<p>Sorry, you have been blacklisted.</p>';
        if (!empty($this->values)) {
            $message .= '<p><a href="http://www.projecthoneypot.org/">Project Honey Pot</a> has determined that you are one or more of the following:</p>';
            $message .= "\n<ul>\n";
            if (!empty($this->values['search_engine'])) {
                $message .= "<li>Search Engine</li>\n";
            }
            if (!empty($this->values['suspicious'])) {
                $message .= "<li>Suspicious Person</li>\n";
            }
            if (!empty($this->values['harvester'])) {
                $message .= "<li>Harvester</li>\n";
            }
            if (!empty($this->values['comment_spammer'])) {
                $message .= "<li>Comment Spammer</li>\n";
            }
            $message .= "\n</ul>\n";
            $message .= '<p>If you feel this is in error, please contact Project Honey Pot or the administrator of this site.</p>';
        }

        @session_write_close();
        header('HTTP/1.1 403 Forbidden');
        echo "<html>\n<head>\n<title>Access Denied</title>\n</head>\n<body>\n".$message."\n</body>\n</html>";
        exit();
    }


    /**
     * Octet-reverse the IP address
     * 
     * @param  $ip The IP to reverse
     * @return bool|string Either a false value or the reversed IP
     */
    protected function _reverseIp($ip) {
        if (!is_numeric(str_replace('.', '', $ip))) {
            return false;
        }
        $ip = explode('.', $ip);

        if (count($ip) != 4) {
            return false;
        }

        return $ip[3].'.'.$ip[2].'.'.$ip[1].'.'.$ip[0];
    }

    /**
     * Get the current IP of the client
     *
     * @return string The client's IP address
     */
    protected function _getIp() {
        $ip = $_SERVER['REMOTE_ADDR'];
        if ($ip == '::1') $ip = '127.0.0.1';
        $ip = '109.230.213.114';
        return $ip;
    }
}
}