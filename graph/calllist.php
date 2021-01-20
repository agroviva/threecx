<?php
require_once(__DIR__."/../api/app.php");

use AgroEgw\Framework;
use AgroEgw\Api\Enqueue;
use AgroEgw\DB;
use AgroEgw\Api\Infolog;
use ThreeCX\Manager as ThreeCXManager;
use ThreeCX\Request;
use AgroEgw\Api\Timesheet;

// Framework::Header();

if (!Request::isLoggedIn()) {
	header("Location: /egroupware/threecx/graph/settings.php");
	exit;
}


Require(__DIR__."/header.php");

// Dump(Timesheet::Config() );
// exit;
ThreeCXManager::$client = Request::$client;

$calllog = ThreeCXManager::$client->request("GET", Request::$URL."/api/CallLog?TimeZoneName=Europe%2FBerlin&callState=All&dateRangeType=LastSevenDays&fromFilter=&fromFilterType=Any&numberOfRows=200&searchFilter=&startRow=0&toFilter=&toFilterType=Any");

$calllogObj = json_decode((string)$calllog->getBody());

ThreeCXManager::addCall($calllogObj);

require __DIR__.'/modals.php';
?>
<div>
	<table class="table table-shopping" style="table-layout: fixed;width: auto;">
		<thead>
			<tr>
				<th>Datum & Uhrzeit</th>
				<th>
					<div class="form-group">
				      <label for="inputFrom">Anrufer</label>
				      <input type="text" name="from" class="form-control" id="inputFrom">
				    </div>
				</th>
				<th>
					<div class="form-group">
				      <label for="inputTo">Anrufende</label>
				      <input type="text" name="to" class="form-control" id="inputTo">
				    </div>
				</th>
				<th style="width: 100px;">Dauer</th>
				<th></th>
				<th></th>
				<th></th>
			</tr>
		</thead>
		<tbody class="calls">
		<?
		$List = ThreeCXManager::getList();
		foreach ($List as $key => $call) {
			$call = (object) $call;

			$CallID = $call->CallID;
			$unixtime = $call->CallTime;
			$date = date("d.m.Y H:i:s", $unixtime);
	 
			$CallerID = $call->CallerId;
			$Destination = $call->Destination;
			$Duration = $call->Duration;

			$marked = ThreeCXManager::isCallMarked($CallID) ? 'data-marked="true"' : '';
			// Dump($call);
			?>
			<tr class="call" data-id="<?=$CallID?>" <?=$marked?>>
				<td><?=$date?></td>
				<td class="call_id" data-number="<?=ThreeCXManager::parseNumber($CallerID)?>" data-title="<?=ThreeCXManager::getTitle($CallerID)?>"><?=$CallerID?></td>
				<td class="destination" data-number="<?=ThreeCXManager::parseNumber($Destination)?>" data-title="<?=ThreeCXManager::getTitle($Destination)?>"><?=$Destination?></td>
				<td><?=ThreeCXManager::time_from_seconds($Duration)?></td>
				<td class="td-actions text-right">
					<button type="button" rel="tooltip" class="btn btn-info btn-simple" onclick="CreateInfolog(this)" data-toggle="modal" data-target="#CreateInfolog">Infolog</button>
				</td>
				<td class="td-actions text-right">
					<button type="button" rel="tooltip" class="btn btn-success btn-simple" onclick="CreateTimesheet(this)" data-toggle="modal" data-target="#CreateTimesheet">Stundenzettel</button>
				</td>
				<td class="td-actions">
					<button onclick="Mark(this)" type="button" rel="tooltip" class="btn" style="padding: 8px;"><i class="material-icons" style="margin-top: 0; margin-bottom: 0;">check_circle</i></button>
				</td>
			</tr>
			<?
		}
		?>
		</tbody>
	</table>
</div>
<?
// Framework::Footer()();
?>
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
	<script src="/egroupware/threecx/js/Infolog.js" type="text/javascript"></script>
	<script src="/egroupware/threecx/js/Timesheet.js" type="text/javascript"></script>
	<script>

		function Mark(elem){
			elem = $(elem).parent().parent();
			$.post("/egroupware/threecx/graph/ajax.php", {
				"action": "markID",
				"callid": elem.attr("data-id")
			}, function(data){
				if (data.response == "success") {
					elem.attr('data-marked', "true");
				}
			});	
		}

		function processRelativeTime$2(number, withoutSuffix, key, isFuture) {
			var format = {
				'm': ['eine Minute', 'einer Minute'],
				'h': ['eine Stunde', 'einer Stunde'],
				'd': ['ein Tag', 'einem Tag'],
				'dd': [number + ' Tage', number + ' Tagen'],
				'M': ['ein Monat', 'einem Monat'],
				'MM': [number + ' Monate', number + ' Monaten'],
				'y': ['ein Jahr', 'einem Jahr'],
				'yy': [number + ' Jahre', number + ' Jahren']
			};
			return withoutSuffix ? format[key][0] : format[key][1];
		}
		moment.defineLocale('de', {
			months: 'Januar_Februar_März_April_Mai_Juni_Juli_August_September_Oktober_November_Dezember'.split('_'),
			monthsShort: 'Jan._Feb._März_Apr._Mai_Juni_Juli_Aug._Sep._Okt._Nov._Dez.'.split('_'),
			monthsParseExact: true,
			weekdays: 'Sonntag_Montag_Dienstag_Mittwoch_Donnerstag_Freitag_Samstag'.split('_'),
			weekdaysShort: 'So._Mo._Di._Mi._Do._Fr._Sa.'.split('_'),
			weekdaysMin: 'So_Mo_Di_Mi_Do_Fr_Sa'.split('_'),
			weekdaysParseExact: true,
			longDateFormat: {
				LT: 'HH:mm',
				LTS: 'HH:mm:ss',
				L: 'DD.MM.YYYY',
				LL: 'D. MMMM YYYY',
				LLL: 'D. MMMM YYYY HH:mm',
				LLLL: 'dddd, D. MMMM YYYY HH:mm'
			},
			calendar: {
				sameDay: '[heute um] LT [Uhr]',
				sameElse: 'L',
				nextDay: '[morgen um] LT [Uhr]',
				nextWeek: 'dddd [um] LT [Uhr]',
				lastDay: '[gestern um] LT [Uhr]',
				lastWeek: '[letzten] dddd [um] LT [Uhr]'
			},
			relativeTime: {
				future: 'in %s',
				past: 'vor %s',
				s: 'ein paar Sekunden',
				ss: '%d Sekunden',
				m: processRelativeTime$2,
				mm: '%d Minuten',
				h: processRelativeTime$2,
				hh: '%d Stunden',
				d: processRelativeTime$2,
				dd: processRelativeTime$2,
				M: processRelativeTime$2,
				MM: processRelativeTime$2,
				y: processRelativeTime$2,
				yy: processRelativeTime$2
			},
			dayOfMonthOrdinalParse: /\d{1,2}\./,
			ordinal: '%d.',
			week: {
				dow: 1, // Monday is the first day of the week.
				doy: 4 // The week that contains Jan 4th is the first week of the year.
			}
		});

		function CreateInfolog(elem) {
			Infolog.Create(elem);
		}

		function CreateTimesheet(elem) {
			Timesheet.Create(elem);
		}

		var infolog = new Infolog();
		var timesheet = new Timesheet();

		function filterResults(){
			var From = $("#inputFrom").val().toLowerCase();
			var To = $("#inputTo").val().toLowerCase();

			$(".calls .call").each(function(index, elem){
				if (
					$(elem).find(".call_id").text().toLowerCase().indexOf(From) >= 0 &&
					$(elem).find(".destination").text().toLowerCase().indexOf(To) >= 0
					) {
					$(elem).removeAttr("hidden");
				} else {
					$(elem).attr("hidden", "true");
				}
			});
		}

		$("#inputFrom, #inputTo").keypress(function(e) {
            if (e.which == 13) {
               filterResults();
            }
        });
	</script>
</body>
</html>
