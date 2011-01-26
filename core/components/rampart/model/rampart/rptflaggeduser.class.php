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
 */
class rptFlaggedUser extends xPDOSimpleObject {

    public function sendActivationEmail(Rampart &$rampart) {
        $user = $this->getOne('User');
        if (empty($user)) return false;
        $profile = $user->getOne('Profile');
        if (empty($profile)) return false;

        /* get either the register's email tpl/subject, or use a default */
        $activationEmailTpl = $this->get('activation_email_tpl');
        if (empty($activationEmailTpl)) {
            $activationEmailTpl = $this->xpdo->getOption('rampart.activation_email_tpl',null,'rptActivationEmail');
        }
        $activationEmailSubject = $this->get('activation_email_subject');
        if (empty($activationEmailSubject)) {
            $activationEmailSubject = $this->xpdo->getOption('rampart.activation_email_subject',null,'Please Activate Your Account');
        }
        $activationResourceId = $this->get('activation_resource_id');
        if (empty($activationResourceId)) $activationResourceId = 1;
        $password = $this->get('password');
        $password = $rampart->decrypt($password);

        /* generate a password and encode it and the username into the url */
        $activationKey = $rampart->generatePassword();
        $confirmParams = array();
        $confirmParams['lp'] = urlencode(base64_encode($activationKey));
        $confirmParams['lu'] = urlencode(base64_encode($user->get('username')));

        /* generate confirmation url */
        $resource = $this->xpdo->getObject('modResource',$activationResourceId);
        if (empty($resource)) return false;
        $this->xpdo->switchContext($resource->get('context_key'));
        $confirmUrl = $this->xpdo->makeUrl($activationResourceId,'',$confirmParams,'full');
        $this->xpdo->switchContext('mgr');

        /* set confirmation email properties */
        $emailProperties = $user->toArray();
        $emailProperties['confirmUrl'] = $confirmUrl;
        $emailProperties['password'] = $password;

        /* now set new password to registry to prevent middleman attacks.
         * Will read from the registry on the confirmation page. */
        $this->xpdo->getService('registry', 'registry.modRegistry');
        $this->xpdo->registry->addRegister('login','registry.modFileRegister');
        $this->xpdo->registry->login->connect();
        $this->xpdo->registry->login->subscribe('/useractivation/');
        $this->xpdo->registry->login->send('/useractivation/',array($user->get('username') => $pword),array(
            'ttl' => ($this->xpdo->getOption('activationttl',$scriptProperties,180)*60),
        ));
        /* set cachepwd here to prevent re-registration of inactive users */
        $user->set('cachepwd',md5($activationKey));
        if (!$user->save()) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,'[Rampart] Could not update cachepwd for activation for User: '.$user->get('username'));
            return false;
        }

        /* send activation email */
        $email = $profile->get('email');
        $msg = $rampart->getChunk($activationEmailTpl,$emailProperties);
        $this->xpdo->getService('mail', 'mail.modPHPMailer');
        $this->xpdo->mail->set(modMail::MAIL_BODY, $msg);
        $this->xpdo->mail->set(modMail::MAIL_FROM, $this->xpdo->getOption('emailsender'));
        $this->xpdo->mail->set(modMail::MAIL_FROM_NAME, $this->xpdo->getOption('site_name'));
        $this->xpdo->mail->set(modMail::MAIL_SENDER, $this->xpdo->getOption('emailsender'));
        $this->xpdo->mail->set(modMail::MAIL_SUBJECT,$activationEmailSubject);
        $this->xpdo->mail->address('to', $email, $profile->get('fullname'));
        $this->xpdo->mail->address('reply-to', $this->xpdo->getOption('emailsender'));
        $this->xpdo->mail->setHTML(true);
        $sent = $this->xpdo->mail->send();
        $this->xpdo->mail->reset();

        return $sent;
    }
}