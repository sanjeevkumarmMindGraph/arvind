<?php
/*********************************************************************
    logout.php

    Destroy clients session.

    pavan kumar
    Copyright (c)  2006-2013 mindgraph
    http://www.mindgraph.com

    Released under the GNU General Public License WITHOUT ANY WARRANTY.
    See LICENSE.TXT for details.

    vim: expandtab sw=4 ts=4 sts=4:
**********************************************************************/

require('client.inc.php');
//Check token: Make sure the user actually clicked on the link to logout.
if ($thisclient && $_GET['auth'] && $ost->validateLinkToken($_GET['auth']))
   $thisclient->logOut();

osTicketSession::destroyCookie();
session_destroy();
Http::redirect('index.php');
?>
