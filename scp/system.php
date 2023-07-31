<?php
/*********************************************************************
    system.php

    System information about this http server, database, php, and osticket.
    Includes useful configuration informations.

    pavan kumar
    Jared Hancock <jared@osticket.com>
    Copyright (c)  2006-2013 mindgraph
    http://www.mindgraph.com

    Released under the GNU General Public License WITHOUT ANY WARRANTY.
    See LICENSE.TXT for details.

    vim: expandtab sw=4 ts=4 sts=4:
**********************************************************************/
require('admin.inc.php');

$page='system.inc.php';
$nav->setTabActive('dashboard');
require(STAFFINC_DIR.'header.inc.php');
require(STAFFINC_DIR.$page);
include(STAFFINC_DIR.'footer.inc.php');
?>
