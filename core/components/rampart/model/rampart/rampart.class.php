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
 * The base class for Rampart.
 *
 * @package rampart
 */
class Rampart {
    const REASON = 'reason';
    const STATUS = 'status';
    const IP = 'ip';
    const HOSTNAME = 'hostname';
    const EMAIL = 'email';
    const USERNAME = 'username';
    const USER_AGENT = 'user_agent';
    const STATUS_OK = 'ok';
    const STATUS_BANNED = 'banned';
    const STATUS_MODERATED = 'moderated';

    function __construct(modX &$modx,array $config = array()) {
        $this->modx =& $modx;

        $corePath = $this->modx->getOption('rampart.core_path',$config,$this->modx->getOption('core_path').'components/rampart/');
        $assetsUrl = $this->modx->getOption('rampart.assets_url',$config,$this->modx->getOption('assets_url').'components/rampart/');
        $connectorUrl = $assetsUrl.'connector.php';

        $this->config = array_merge(array(
            'assetsUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl.'css/',
            'jsUrl' => $assetsUrl.'js/',
            'imagesUrl' => $assetsUrl.'images/',

            'connectorUrl' => $connectorUrl,

            'corePath' => $corePath,
            'modelPath' => $corePath.'model/',
            'chunksPath' => $corePath.'elements/chunks/',
            'chunkSuffix' => '.chunk.tpl',
            'snippetsPath' => $corePath.'elements/snippets/',
            'processorsPath' => $corePath.'processors/',
            'controllersPath' => $corePath.'controllers/',

            'salt' => $this->modx->getOption('rampart.salt',$config,'sieg3thec4stle'),
        ),$config);

        $this->modx->addPackage('rampart',$this->config['modelPath']);
        $this->modx->lexicon->load('rampart:default');
    }

    /**
     * Initializes modExtra into different contexts.
     *
     * @access public
     * @param string $ctx The context to load. Defaults to web.
     */
    public function initialize($ctx = 'web') {
        switch ($ctx) {
            case 'mgr':
                if (!$this->modx->loadClass('rampart.request.rampartControllerRequest',$this->config['modelPath'],true,true)) {
                    return 'Could not load controller request handler.';
                }
                $this->request = new RampartControllerRequest($this);
                return $this->request->handleRequest();
            break;
            default:
            break;
        }
    }

    /**
     * Gets a Chunk and caches it; also falls back to file-based templates
     * for easier debugging.
     *
     * @access public
     * @param string $name The name of the Chunk
     * @param array $properties The properties for the Chunk
     * @return string The processed content of the Chunk
     */
    public function getChunk($name,array $properties = array()) {
        $chunk = null;
        if (!isset($this->chunks[$name])) {
            $chunk = $this->modx->getObject('modChunk',array('name' => $name),true);
            if (empty($chunk)) {
                $chunk = $this->_getTplChunk($name,$this->config['chunkSuffix']);
                if ($chunk == false) return false;
            }
            $this->chunks[$name] = $chunk->getContent();
        } else {
            $o = $this->chunks[$name];
            $chunk = $this->modx->newObject('modChunk');
            $chunk->setContent($o);
        }
        $chunk->setCacheable(false);
        return $chunk->process($properties);
    }
    /**
     * Returns a modChunk object from a template file.
     *
     * @access private
     * @param string $name The name of the Chunk. Will parse to name.chunk.tpl by default.
     * @param string $suffix The suffix to add to the chunk filename.
     * @return modChunk/boolean Returns the modChunk object if found, otherwise
     * false.
     */
    private function _getTplChunk($name,$suffix = '.chunk.tpl') {
        $chunk = false;
        $f = $this->config['chunksPath'].strtolower($name).$suffix;
        if (file_exists($f)) {
            $o = file_get_contents($f);
            $chunk = $this->modx->newObject('modChunk');
            $chunk->set('name',$name);
            $chunk->setContent($o);
        }
        return $chunk;
    }

    /**
     * Run the spam checks
     */
    public function check($username = '',$email = '') {
        $ip = $_SERVER['REMOTE_ADDR'];
        if ($ip == '::1') $ip = '72.177.93.127';
        /* demo spammer data */
        //$ip = '109.230.213.121';
        //$username = 'RyanHG';
        //$email = 'yumunter@fmailer.net';

        $result = array(
            Rampart::STATUS => Rampart::STATUS_OK,
            Rampart::REASON => '',
            Rampart::IP => $ip,
            Rampart::HOSTNAME => gethostbyaddr($ip),
            Rampart::EMAIL => $email,
            Rampart::USERNAME => $username,
            Rampart::USER_AGENT => $_SERVER['HTTP_USER_AGENT'],
        );

        if (!$this->checkWhiteList($result)) {
            /* check Rampart ban list */
            $result = $this->checkBanList($result);

            /* Run StopForumSpam checks */
            $result = $this->runStopForumSpamChecks($result);
        }
        return $result;
    }

    /**
     * @TODO: Make sql-agnostic for sqlsrv support
     */
    public function checkBanList($result) {
        $boomIp = explode('.',$result[Rampart::IP]);

        /* build spam checking query */
        $c = $this->modx->newQuery('rptBan');
        $c->select($this->modx->getSelectColumns('rptBan','rptBan'));
        $c->select(array(
            'IF("'.$result[Rampart::USERNAME].'" LIKE `rptBan`.`username`,1,0) AS `username_match`',
            'IF("'.$result[Rampart::EMAIL].'" LIKE `rptBan`.`email`,1,0) AS `email_match`',
            'IF("'.$result[Rampart::HOSTNAME].'" LIKE `rptBan`.`hostname`,1,0) AS `hostname_match`',
            'IF((('.$boomIp[0].' BETWEEN `rptBan`.`ip_low1` AND `rptBan`.`ip_high1`)
             AND ('.$boomIp[1].' BETWEEN `rptBan`.`ip_low2` AND `rptBan`.`ip_high2`)
             AND ('.$boomIp[2].' BETWEEN `rptBan`.`ip_low3` AND `rptBan`.`ip_high3`)
             AND ('.$boomIp[3].' BETWEEN `rptBan`.`ip_low4` AND `rptBan`.`ip_high4`)),1,0) AS `ip_match`',
        ));
        if (!empty($result[Rampart::USERNAME])) {
            $c->orCondition(array(
                '"'.$result[Rampart::USERNAME].'" LIKE rptBan.username',
            ),null,2);
        }
        if (!empty($result[Rampart::EMAIL])) {
            $c->orCondition(array(
                '"'.$result[Rampart::EMAIL].'" LIKE rptBan.email',
            ),null,2);
        }
        $c->orCondition(array(
            '"'.$result[Rampart::HOSTNAME].'" LIKE rptBan.hostname',
        ),null,2);
        $c->orCondition(array(
              '(('.$boomIp[0].' BETWEEN `rptBan`.`ip_low1` AND `rptBan`.`ip_high1`)
            AND ('.$boomIp[1].' BETWEEN `rptBan`.`ip_low2` AND `rptBan`.`ip_high2`)
            AND ('.$boomIp[2].' BETWEEN `rptBan`.`ip_low3` AND `rptBan`.`ip_high3`)
            AND ('.$boomIp[3].' BETWEEN `rptBan`.`ip_low4` AND `rptBan`.`ip_high4`))'
        ),null,2);
        $c->where(array(
            'active' => true,
        ));
        $c->andCondition(array(
            'expireson:>' => time(),
            'OR:expireson:IS' => null,
            'OR:expireson:=' => '',
        ),null,3);

        $bans = $this->modx->getCollection('rptBan',$c);
        if (count($bans)) {
            $matches = array();
            $fieldMatches = array();
            foreach ($bans as $ban) {
                if ($ban->get('ip_match')) {
                    $fieldMatches['ip'] = $ban->get('ip');
                    $matches[$ban->get('id')] = 'ip';
                }
                if ($ban->get('username_match')) {
                    $fieldMatches['username'] = $ban->get('username');
                    $matches[$ban->get('id')] = 'username';
                }
                if ($ban->get('hostname_match')) {
                    $fieldMatches['hostname'] = $ban->get('hostname');
                    $matches[$ban->get('id')] = 'hostname';
                }
                if ($ban->get('email_match')) {
                    $fieldMatches['email'] = $ban->get('email');
                    $matches[$ban->get('id')] = 'email';
                }

                $ban->set('matches',$ban->get('matches')+1);
                $ban->save();
            }

            $match = $this->modx->newObject('rptBanMatch');
            $match->set('ip',$result[Rampart::IP]);
            $match->set('hostname',$result[Rampart::HOSTNAME]);
            $match->set('username',$result[Rampart::USERNAME]);
            $match->set('email',$result[Rampart::EMAIL]);
            $match->set('useragent',$result[Rampart::USER_AGENT]);

            if (!empty($fieldMatches['ip'])) $match->set('ip_match',$fieldMatches['ip']);
            if (!empty($fieldMatches['username'])) $match->set('username_match',$fieldMatches['username']);
            if (!empty($fieldMatches['hostname'])) $match->set('hostname_match',$fieldMatches['hostname']);
            if (!empty($fieldMatches['email'])) $match->set('email_match',$fieldMatches['email']);

            $match->set('resource',$this->modx->resource->get('id'));
            $match->set('createdon',time());

            if ($match->save()) {
                foreach ($matches as $banId => $field) {
                    $bmb = $this->modx->newObject('rptBanMatchBan');
                    $bmb->set('ban',$banId);
                    $bmb->set('ban_match',$match->get('id'));
                    $bmb->set('field',$field);
                    $bmb->save();
                }
            }
            $result[Rampart::STATUS] = Rampart::STATUS_BANNED;
        }
        return $result;
    }


    public function runStopForumSpamChecks(array $result = array()) {
        /* Run StopForumSpam checks */
        if ($this->modx->loadClass('stopforumspam.RampartStopForumSpam',$this->config['modelPath'],true,true)) {
            $sfspam = new RampartStopForumSpam($this->modx);
            $spamResult = $sfspam->check($result[Rampart::IP],$result[Rampart::EMAIL],$result[Rampart::USERNAME]);
            if (!empty($spamResult)) {
                if (in_array('Ip',$spamResult) && in_array('Username',$spamResult)) {
                    /**
                     * If ip AND username match, moderate user
                     */
                    $result[Rampart::STATUS] = Rampart::STATUS_MODERATED;
                    $result[Rampart::REASON] = 'ipusername';
                } else if (in_array('Email',$spamResult)) {
                    /**
                     * Moderate users who match the email
                     */
                    $result[Rampart::STATUS] = Rampart::STATUS_MODERATED;
                    $result[Rampart::REASON] = 'email';
                } else if (in_array('Ip',$spamResult)) {
                    $threshold = $this->modx->getOption('rampart.sfs_ipban_threshold',null,25); /* threshold of reported times by SFS */
                    $expiration = $this->modx->getOption('rampart.sfs_ipban_expiration',null,30); /* # of days to ban */
                    if ($threshold > 0) {
                        /**
                         * If the IP of the spammer shows up past our threshold
                         * of frequency times that StopForumSpam reports as,
                         * add a single-ip ban for a certain amount of time
                         */
                        $ipResult = $sfspam->check($result[Rampart::IP]);
                        if (!empty($ipResult)) {
                            $ips = $sfspam->responseXml;
                            $frequency = (int)$ips->frequency;
                            if ($frequency >= $threshold) {
                                $this->addBan($result[Rampart::IP],'StopForumSpam IP Ban',$expiration);
                                $result[Rampart::STATUS] = Rampart::STATUS_BANNED;
                                $result[Rampart::REASON] = 'sfsip';
                            }
                        }
                    }
                }
            }
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[Rampart] Could not load StopForumSpam class.');
        }
        return $result;
    }

    /**
     * Check to see if an IP is on the WhiteList
     * 
     * @param string $ip The IP of the client to check against
     * @param string $email The email to also check against
     * @return bool True if found on the WhiteList
     */
    public function checkWhiteList(array $result = array()) {
        $c = $this->modx->newQuery('rptWhiteList');
        $c->where(array(
            'ip' => $result[Rampart::IP],
            'active' => true,
        ));
        $count = $this->modx->getCount('rptWhiteList',$c);
        return $count > 0;
    }

    /**
     * Generate a random key password
     */
    public function generatePassword($length=8) {
        $pword = '';
        $charmap = '0123456789bcdfghjkmnpqrstvwxyz';
        $i = 0;
        while ($i < $length) {
            $char = substr($charmap, rand(0, strlen($charmap)-1), 1);
            if (!strstr($pword, $char)) {
                $pword .= $char;
                $i++;
            }
        }
        return $pword;
    }

    /**
     * Add a ban to the banlist
     */
    public function addBan($ip,$reason,$expires = 30,$lastActive = null,array $data = array()) {
        $future = time() + ($expires * 24 * 60 * 60);
        if (empty($lastActive)) $lastActive = time();

        $ban = $this->modx->getObject('rptBan',array(
            'ip' => $ip,
        ));
        if ($ban) {
            $matches = (int)$ban->get('matches') + 1;
            $ban->set('matches',$matches);
        } else {
            $ban = $this->modx->newObject('rptBan');
            $ban->set('reason',$reason);
            $ban->set('createdon',time());
            $ban->set('active',true);
            $boomIp = explode('.',$ip);
            $ban->set('ip_low1',$boomIp[0]);
            $ban->set('ip_high1',$boomIp[0]);
            $ban->set('ip_low2',$boomIp[1]);
            $ban->set('ip_high2',$boomIp[1]);
            $ban->set('ip_low3',$boomIp[2]);
            $ban->set('ip_high3',$boomIp[2]);
            $ban->set('ip_low4',$boomIp[3]);
            $ban->set('ip_high4',$boomIp[3]);
            $ban->set('matches',1);
        }
        $ban->set('ip',$ip);
        $ban->set('expireson',$future);
        $ban->set('last_activity',$lastActive);
        $ban->set('data',$data);
        return $ban->save();
    }



    /**
     * Encrypts a string with a md5/mcrypt salted hash
     *
     * @access private
     * @param string $str The string to encrypt
     * @return An encrypted, salted hash
     */
    public function encrypt($str) {
        $key = $this->config['salt'];

        srand((double)microtime() * 1000000); /* for MCRYPT_RAND */
        $key = md5($key); /* to improve variance */

        /* open module, create IV */
        $td = mcrypt_module_open('des','','cfb','');
        $key = substr($key,0,mcrypt_enc_get_key_size($td));
        $iv_size = mcrypt_enc_get_iv_size($td);
        $iv = mcrypt_create_iv($iv_size,MCRYPT_RAND);

        /* initialize encryption handle */
        if (mcrypt_generic_init($td,$key,$iv) != -1) {
            /* Encrypt data */
            $c_t = mcrypt_generic($td,$str);
            mcrypt_generic_deinit($td);
            mcrypt_module_close($td);
            $c_t = $iv.$c_t;
            return urlencode($c_t);
        }
    }

    /**
     * Decrypts a string based upon the set hash
     *
     * @access private
     * @param string $str The string to decrypt
     * @return A decrypted string
     */
    public function decrypt($str) {
        $str = urldecode($str);
        $key = $this->config['salt'];

        $key = md5($key);

        /* open module, create IV */
        $td = mcrypt_module_open('des','','cfb','');
        $key = substr($key,0,mcrypt_enc_get_key_size($td));
        $iv_size = mcrypt_enc_get_iv_size($td);
        $iv = substr($str,0,$iv_size);
        $str = substr($str,$iv_size);

        /* initialize encryption handle */
        if (mcrypt_generic_init($td,$key,$iv) != -1) {
            /* decrypt data */
            $c_t = mdecrypt_generic($td,$str);
            mcrypt_generic_deinit($td);
            mcrypt_module_close($td);
            return $c_t;
        }
    }

}