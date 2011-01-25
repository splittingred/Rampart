<?php
/**
 * preHook for Register snippet that utilizes Rampart
 * 
 * @package rampart
 */
$modelPath = $modx->getOption('rampart.core_path',null,$modx->getOption('core_path').'components/rampart/').'model/';
$rampart = $modx->getService('rampart','Rampart',$modelPath.'rampart/');
$isBanned = false;
$isRestricted = false;

$username = $fields[$usernameField];
//var_dump($fields);

$email = $fields['email'];

$ip = $_SERVER['REMOTE_ADDR'];
if ($ip == '::1') $ip = '72.177.93.127';
$boomIp = explode('.',$ip);
$hostname = gethostbyaddr($ip);

/* build spam checking query */
$c = $modx->newQuery('rptBan');
$c->where(array(
    '"'.$username.'" LIKE username',
));
$c->orCondition(array(
    '"'.$email.'" LIKE email',
));
$c->orCondition(array(
    '"'.$hostname.'" LIKE hostname',
));
$c->orCondition(array(
    '(('.$boomIp[0].' BETWEEN ip_low1 AND ip_high1)
	AND ('.$boomIp[1].' BETWEEN ip_low2 AND ip_high2)
	AND ('.$boomIp[2].' BETWEEN ip_low3 AND ip_high3)
	AND ('.$boomIp[3].' BETWEEN ip_low4 AND ip_high4))'
));

$bans = $modx->getCollection('rptBan',$c);
if (count($bans)) {
    foreach ($bans as $ban) {
        $ban->set('matches',$ban->get('matches')+1);
        $ban->save();
    }
    $isBanned = true;
}

//var_dump($hostname);

/* demo spammer data */
//$ip = '109.230.213.121';
//$username = 'RyanHG';
//$email = 'yumunter@fmailer.net';

/* Run StopForumSpam checks */
if ($modx->loadClass('stopforumspam.StopForumSpam',$rampart->config['modelPath'],true,true)) {
    $sfspam = new StopForumSpam($modx);
    $spamResult = $sfspam->check($ip,$email,$username);
    if (!empty($spamResult)) {
        if (in_array('Ip',$spamResult) && in_array('Username',$spamResult)) {
            $isRestricted = true;
        } else if (in_array('Email',$spamResult)) {
            $isRestricted = true;
        } else if (in_array('Ip',$spamResult)) {
            /* here we would add a "threshold" of sorts, if an IP positive happens
             * a lot, we would add to the ban list
             */
        }
    }
} else {
    $modx->log(modX::LOG_LEVEL_ERROR,'[Rampart] Couldnt load StopForumSpam class.');
}

if ($isBanned) {
    die('SPAM');
    $hook->addError('username','SPAMMER! Please go away.');
    return false;
}
if ($isRestricted) {
    die('RESTRICTED');
    /* need to implement this in Register to prevent conf email from being sent */
    $hook->setValue('register.moderate',true);
    return true;
}

//echo count($bans).'<br />';
die('NO BAN! RAMPART!!');
return true;