<?php
use EGroupware\Api\Header\ContentSecurityPolicy as CSP;

require dirname(__DIR__).'/api/app.php';

CSP::add_script_src(array('self', 'unsafe-eval', 'unsafe-inline'));

$user = (object) $GLOBALS["egw_info"]["user"];

$username = $user->account_lid;
$fullname = $user->account_firstname." ".$user->account_lastname;
$contact_id = $user->person_id;
$account_id = $user->account_id;
// Dump($user);
?>
<link rel="stylesheet" type="text/css" href="/egroupware/threecx/css/ProductiveTime.css">
<script src="/egroupware/threecx/material/assets/js/core/jquery.min.js" type="text/javascript"></script>
<script src="/egroupware/threecx/js/ProductiveTime.js" type="text/javascript"></script>
<div id="ProductiveTime" style="display: none;">
	<div class="UserForm">
		<div class="benutzer">
			<img class="benutzer_photo" src="/egroupware/index.php?menuaction=addressbook.addressbook_ui.photo&contact_id=<?php echo $contact_id?>&etag=12" alt="" title="" style="display: inline;">
			<font class="benutzer_fullname"><?php echo $fullname?></font>
			<div class="progressBar light-grey">
			  <div class="green" style="height:24px;width:25%"></div>
			</div>
		</div>
	</div>
</div>