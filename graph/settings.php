<?php
Include_Once(__DIR__."/../api/app.php");

use AgroEgw\Framework;
use AgroEgw\Api\Enqueue;
use AgroEgw\DB;
use AgroEgw\Api\Infolog;
use ThreeCX\Manager as ThreeCXManager;
use ThreeCX\Request;
use AgroEgw\Api\Timesheet;

// Framework::Header();
function str_random($length = 16)
{
    $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
}

$Data = (new DB("SELECT * FROM egw_threecx WHERE id = 1"))->Fetch();


if (!empty($_POST)) {
	header('Content-Type: application/json');
	$attr = $_POST;
	$randomString = str_random();
	$attr["token"] = $randomString;
	$settings = json_encode($attr);

	if (empty($attr["url"]) || empty($attr["username"] || empty($attr["password"]))) {
		echo json_encode(array("responde" => "failure"));
	} else {
		if (empty($Data)) {
			(new DB("INSERT INTO egw_threecx (id, data) VALUES (1, '$settings')"));
		} else {
			(new DB("UPDATE egw_threecx SET data = '$settings' WHERE id = 1"));
		}

		$Data = (new DB("SELECT * FROM egw_threecx WHERE id = 1"))->Fetch();
		if (!empty($Data)) {
			$loggedIn = Request::isLoggedIn();
		} else {
			$loggedIn = false;
		}

		if ($loggedIn) {
			echo json_encode(array("responde" => "success"));
		} else {
			echo json_encode(array("responde" => "failure"));
		}
		
	}
	exit;
}

if (!empty($Data)) {
	$login = json_decode($Data["data"], true);
	$loggedIn = Request::isLoggedIn();
} else {
	$loggedIn = false;
}

Require(__DIR__."/header.php");

?>

	<div class="container">
		<?php if (!$loggedIn): ?>
		<div class="text-danger text-center">Die Zugangsdaten sind falsch!</div>
		<?php endif ?>
		<form>
			<div class="form-group">
		      <label for="inputURL">3CX URL</label>
		      <input type="text" name="url" class="form-control" id="inputURL" value="<?php echo $login["url"]?>" required>
		    </div>
		    <div class="form-row">
			    <div class="form-group col-md-6">
			      <label for="inputUsername">Benutzername</label>
			      <input type="text" name="username" class="form-control" id="inputUsername" value="<?php echo $login["username"]?>" required>
			    </div>
			    <div class="form-group col-md-6">
			      <label for="inputPassword">Passwort</label>
			      <input type="password" name="password" class="form-control" id="inputPassword" required>
			    </div>
			</div>
		</form>
		<button class="btn btn-primary submit-form">Speichern</button>
	</div>

	<!--   Core JS Files   -->
	<script src="/egroupware/threecx/material/assets/js/core/jquery.min.js" type="text/javascript"></script>
	<script src="/egroupware/threecx/material/assets/js/core/popper.min.js" type="text/javascript"></script>
	<script src="/egroupware/threecx/material/assets/js/core/bootstrap-material-design.min.js" type="text/javascript"></script>
	<script src="/egroupware/threecx/material/assets/js/plugins/moment.min.js"></script>
	<!--	Plugin for the Datepicker, full documentation here: https://github.com/Eonasdan/bootstrap-datetimepicker -->
	<script src="/egroupware/threecx/material/assets/js/plugins/bootstrap-datetimepicker.js" type="text/javascript"></script>
	<!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
	<script src="/egroupware/threecx/material/assets/js/plugins/nouislider.min.js" type="text/javascript"></script>
	<!--	Plugin for Sharrre btn -->
	<script src="/egroupware/threecx/material/assets/js/plugins/jquery.sharrre.js" type="text/javascript"></script>
	<!-- Control Center for Material Kit: parallax effects, scripts for the example pages etc -->
	<script src="/egroupware/threecx/material/assets/js/material-kit.js?v=2.0.4" type="text/javascript"></script>
	<script src="/egroupware/threecx/js/sweetalert.min.js" type="text/javascript"></script>
	<script>
		$("button.submit-form").click(function(e){
			e.preventDefault();
			$.post(window.location.href, $("form").serialize(), function(data){
				if (data.responde == "success") {
					window.location.href = "/egroupware/threecx/graph/index.php";
				} else {
					swal("Fehler!", {
					  text: "Wir konnten keine Verbindung mit 3CX erstellen. Bitte überprüfen Sie Ihre Zugangsdaten!",
					  button: false,
					  icon: "error",
					  timer: 2000
					});
				}
			});
		});
	</script>
</body>
</html>