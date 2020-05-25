<?php

$_GET['cd'] = 'no';
$GLOBALS['egw_info']['flags'] = array(
        'currentapp'    => 'threecx',
        'noheader'   => True,
        'nonavbar'   => True,
);
Include('../header.inc.php');
$GLOBALS['egw']->redirect_link('/threecx/graph/index.php');