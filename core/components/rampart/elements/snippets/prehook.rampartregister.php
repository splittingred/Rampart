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
            $isRestricted = 'ipusername';
        } else if (in_array('Email',$spamResult)) {
            $isRestricted = 'email';
        } else if (in_array('Ip',$spamResult)) {
            /* TODO: here we would add a "threshold" of sorts, if an IP positive
             * happens a lot, we would add to the ban/flagged list
             */
        }
    }
} else {
    $modx->log(modX::LOG_LEVEL_ERROR,'[Rampart] Couldnt load StopForumSpam class.');
}

$hook->setValue('ip',$ip);
$hook->setValue('hostname',$hostname);
$hook->setValue('userAgent',$_SERVER['HTTP_USER_AGENT']);

if ($isBanned) {
    $hook->addError('username','SPAMMER! Please go away.');
    return false;
}
if (!empty($isRestricted)) {
    /* prevents confirmation email from being sent */
    $hook->setValue('register.moderate',true);

    /* create a flagged user record */
    $flu = $modx->newObject('rptFlaggedUser');
    $flu->set('username',$username);
    $flu->set('flaggedon',time());
    $flu->set('flaggedfor',$isRestricted);
    $flu->set('ip',$ip);
    $flu->set('hostname',$hostname);
    $flu->set('useragent',$_SERVER['HTTP_USER_AGENT']);
    $flu->save();
    return true;
}

die('NO BAN! RAMPART!!');
return true;