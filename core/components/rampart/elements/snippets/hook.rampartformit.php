<?php
/**
 * Hook for FormIt forms
 *
 * @var modX $modx
 * @var Rampart $rampart
 * @var array $scriptProperties
 * @var fiHooks $hook
 * @var array $fields
 * @package rampart
 */
$modelPath = $modx->getOption('rampart.core_path',null,$modx->getOption('core_path').'components/rampart/').'model/';
$rampart = $modx->getService('rampart','Rampart',$modelPath.'rampart/');

/* setup default properties */
$rptErrorField = $modx->getOption('rptErrorField',$scriptProperties,'email');
$rptUsernameField = $modx->getOption('rptUsernameField',$scriptProperties,'username');
$rptEmailField = $modx->getOption('rptEmailField',$scriptProperties,'email');
$rptSpammerErrorMessage = $modx->getOption('rptSpammerErrorMessage',$scriptProperties,'Your account has been banned as a spammer. Sorry.');

/* get username/email if they exist */
$username = '';
if (!empty($fields[$rptUsernameField])) { $username = $fields[$rptUsernameField]; }
$email = '';
if (!empty($fields[$rptEmailField])) { $email = $fields[$rptEmailField]; }

/* run ban checking */
$response = $rampart->check($username,$email);

if ($response[Rampart::STATUS] == Rampart::STATUS_BANNED) {
    $hook->addError($rptErrorField,$rptSpammerErrorMessage);
    return false;
}
return true;
