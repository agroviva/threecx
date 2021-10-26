<?php
use EGroupware\Api;
use EGroupware\Api\Vfs;
use EGroupware\Api\Header\ContentSecurityPolicy as CSP;
use ThreeCX\DB;
use ThreeCX\Request;
use EGroupware\Api\Asyncservice;

use AgroEgw\App;
use AgroEgw\Api\Enqueue;

define('DEBUG_MODE', false);

define('APPDIR', dirname(__DIR__));

Require_Once(__DIR__.'/../../agroviva/vendor/autoload.php');

App::setName("threecx");
App::Start();
Require_Once(__DIR__.'/../../header.inc.php');
Require_Once(__DIR__."/../classes/autoload.php");
Require_Once(__DIR__."/../classes/functions/autoload.php");

$async = new asyncservice();

if (($async->read('threecx')['threecx']['method'] != 'threecx.threecx_ui.synchron')) {
    $async->delete('threecx');
    # $async->set_timer(['min' => '*/5'], 'threecx', 'threecx.threecx_ui.synchron', null);
}

App::Clean();

CSP::add_script_src(array("self","unsafe-inline"));

function JavaScriptLoad()
{
	$time = strtotime(date("Y-m-d", time()));
	?>
	<?php
}

function SideBoxLoad()
{
	?>
<div id="divAppboxHeader" class="onlyPrint"></div><span id="late-sidebox" data-setSidebox="[&quot;threecx&quot;,[{&quot;menu_name&quot;:&quot;7b7bc2512ee1fedcd76bdc68926d4f7b&quot;,&quot;title&quot;:&quot;Administrator&quot;,&quot;entries&quot;:[{&quot;icon_or_star&quot;:&quot;\/egroupware\/pixelegg\/images\/bullet.png&quot;,&quot;target&quot;:&quot;&quot;,&quot;lang_item&quot;:&quot;Anrufsliste&quot;,&quot;item_link&quot;:&quot;javascript:egw_link_handler('\/egroupware\/threecx\/graph\/calllist.php','threecx')&quot;},{&quot;icon_or_star&quot;:&quot;\/egroupware\/pixelegg\/images\/bullet.png&quot;,&quot;target&quot;:&quot;&quot;,&quot;lang_item&quot;:&quot;Einstellungen&quot;,&quot;item_link&quot;:&quot;javascript:egw_link_handler('\/egroupware\/threecx\/graph\/settings.php','threecx')&quot;}],&quot;opened&quot;:true},{&quot;menu_name&quot;:&quot;e3afed0047b08059d0fada10f400c1e5&quot;,&quot;title&quot;:&quot;Admin&quot;,&quot;entries&quot;:[],&quot;opened&quot;:false}],&quot;2ba29f8e06637b868aa1d2dd4cbae64a&quot;]"/>
	<?php
}
