<?php
/*********************************************************************
    emails.php

    Emails

    pavan kumar
    Copyright (c)  2006-2013 mindgraph
    http://www.mindgraph.com

    Released under the GNU General Public License WITHOUT ANY WARRANTY.
    See LICENSE.TXT for details.

    vim: expandtab sw=4 ts=4 sts=4:
**********************************************************************/
require('admin.inc.php');
include_once(INCLUDE_DIR.'class.email.php');

$email=null;
if ($_REQUEST['id']) {
    if (($email=Email::lookup((int) $_REQUEST['id']))) {
        // Get stashed errors or msg (if any)
        if (!($errors=$email->restoreErrors()))
            $msg = $email->restoreNotice() ?: null;
    } else {
        $errors['err'] = sprintf(__('%s: Unknown or invalid ID.'), __('email'));
    }
}

if($_POST){
    switch(strtolower($_POST['do'])){
        case 'update':
            if (!$email){
                $errors['err'] = sprintf(__('%s: Unknown or invalid'), __('email'));
            } elseif ($email->update($_POST, $errors)){
                $msg=sprintf(__('Successfully updated %s.'),
                    __('this email'));
            } elseif (!isset($errors['err'])) {
                $errors['err'] = sprintf('%s %s',
                    sprintf(__('Unable to update %s.'), __('this email')),
                    __('Correct any errors below and try again.'));
            }
            break;
        case 'create':
            $box = Email::create();
            if ($box->update($_POST, $errors)) {
                $email = $box;
                $id = $box->getId();
                $msg=sprintf(__('Successfully added %s.'),
                        Format::htmlchars($email->getAddress()));
                $_REQUEST['a']=null;
                $type = array('type' => 'created');
                Signal::send('object.created', $email, $type);
            } elseif (!$errors['err']) {
                $errors['err']=sprintf('%s %s',
                    sprintf(__('Unable to add %s.'), __('this email')),
                    __('Correct any errors below and try again.'));
            }
            break;
        case 'mass_process':
            if(!$_POST['ids'] || !is_array($_POST['ids']) || !count($_POST['ids'])) {
                $errors['err'] = sprintf(__('You must select at least %s.'),
                    __('one email'));
            } else {
                $count=count($_POST['ids']);

                switch (strtolower($_POST['a'])) {
                case 'delete':
                    $i=0;
                    foreach($_POST['ids'] as $k=>$v) {
                        if($v!=$cfg->getDefaultEmailId() && ($e=Email::lookup($v)) && $e->delete())
                            $i++;
                    }

                    if($i && $i==$count)
                        $msg = sprintf(__('Successfully deleted %s.'),
                            _N('selected email', 'selected emails', $count));
                    elseif($i>0)
                        $warn = sprintf(__('%1$d of %2$d %3$s deleted'), $i, $count,
                            _N('selected email', 'selected emails', $count));
                    elseif(!$errors['err'])
                        $errors['err'] = sprintf(__('Unable to delete %s.'),
                            _N('selected email', 'selected emails', $count));
                    break;

                default:
                    $errors['err'] = sprintf('%s - %s', __('Unknown action'), __('Get technical help!'));
                }
            }
            break;
        default:
            $errors['err'] = __('Unknown action');
            break;
    }
}  elseif (isset($_GET['do'])) {
    switch ($_GET['do']) {
    case 'autho':
        // Lookup external oauth2 backend
        if ($bk=OAuth2AuthorizationBackend::getBackend($_GET['bk']))
            $bk->triggerEmailAuth($_GET['bk']);
    }
    $errors['err'] = sprintf('%s: %s',
            __('Unknown Authorization Backend'),
            __('OAuth2 Plugin must be enabled'));
}

$page='emails.inc.php';
$tip_namespace = 'emails.email';
if ($email || ($_REQUEST['a'] && !strcasecmp($_REQUEST['a'], 'add')))
    $page='email.inc.php';

$nav->setTabActive('emails');
$ost->addExtraHeader('<meta name="tip-namespace" content="' . $tip_namespace . '" />',
    "$('#content').data('tipNamespace', '".$tip_namespace."');");
require(STAFFINC_DIR.'header.inc.php');
require(STAFFINC_DIR.$page);
include(STAFFINC_DIR.'footer.inc.php');
?>
