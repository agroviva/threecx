<?php

$setup_info['threecx']['name']      = 'threecx';
$setup_info['threecx']['title']     = '3CX Schnittstelle';
$setup_info['threecx']['version']   = '16.1.002';
$setup_info['threecx']['app_order'] = 99;
$setup_info['threecx']['tables']    = array('egw_threecx','egw_threecx_meta','egw_threecx_calllist');
$setup_info['threecx']['enable']    = 1;

//The application's hooks rergistered.
$setup_info['threecx']['hooks']['admin'] = 'threecx_hooks::all_hooks';
$setup_info['threecx']['hooks']['sidebox_menu'] = 'threecx_hooks::all_hooks';        /* Dependencies for this app to work */
$setup_info['threecx']['hooks']['search_link'] = 'threecx_hooks::search_link';
$setup_info['threecx']['hooks'][] = 'after_navbar';

$setup_info['threecx']['depends'][] = array(
         'appname' => 'api',
         'versions' => Array('16.1')
);
