<?php
use AgroEgw\Framework;
use AgroEgw\Api\Enqueue;
use AgroEgw\DB;
use AgroEgw\Api\Infolog;
use ThreeCX\Manager as ThreeCXManager;
use ThreeCX\Request;
use AgroEgw\Api\Timesheet;

?>
<link rel="stylesheet" type="text/css" href="/egroupware/threecx/css/after_navbar.css">
<div class="modal fade bd-example-modal-lg" id="CreateInfolog" tabindex="-1" role="">
	<div class="modal-dialog modal-login" role="document">
	    <div class="modal-content">
	        <div class="card card-signup card-plain">
	            <div class="modal-body">
	            	<form>
	            		<input type="hidden" id="CallIDInfo" name="callid">
	            		<div class="form-group">
					      <label for="inputTitleInfo">Title</label>
					      <input type="text" name="info_title" class="form-control" id="inputTitleInfo">
					    </div>
					    <input type="hidden" id="inputNumberInfo" name="tel_number">
					  <div class="form-row">
					  	<div class="form-group col-md-4">
					      <label for="inputTypeInfo">Infolog Typ</label>
					      <select id="inputTypeInfo" name="info_type" class="form-control">
					        <?php foreach (Infolog::InfoTypes() as $key => $type): ?>
					     		<option value="<?php echo $key?>" <?php echo ($firstSeen ? "" : "selected")?>><?php echo $type?></option>
					     		<?php $firstSeen = true; ?>
					        <?php endforeach ?>
					      </select>
					    </div>
					    <div class="form-group col-md-4">
					      <label for="inputStatusInfo">Status</label>
					      <select id="inputStatusInfo" name="info_status" class="form-control">
					        
					      </select>
					    </div>
					    <div class="form-group col-md-4">
					      <label for="inputStartDateInfo">Startdatum</label>
					      <input type="text" name="startdate" class="form-control datetimepicker" id="inputStartDateInfo" value=""/>
					    </div>
					  </div>
					  <div class="form-group">
					    <label for="inputAddressInfo">Adressbuch</label>
					    <input type="text" class="form-control" id="inputAddressInfo">
					    <input type="hidden" id="linked_addresses_info" name="linked_addresses">
					   	<div class="linked_addresses not-selectable"></div>
					  </div>
					  <div class="form-group">
					    <label for="inputResponsibleInfo">Verantwortlich</label>
					    <input type="text" class="form-control" id="inputResponsibleInfo">
					   	<input type="hidden" id="responsible_users_info" name="responsible_users">
					   	<div class="responsible_users not-selectable"></div>
					  </div>
					  <div class="form-group">
					    <label for="descriptionInfo">Beschreibung</label>
					    <textarea type="text" name="info_des" class="form-control" rows="4" id="descriptionInfo"></textarea>
					  </div>
					</form>
					<button class="btn btn-primary">Speichern</button>
	            </div>
	        </div>
	    </div>
	</div>
</div>
<div class="modal fade bd-example-modal-lg" id="CreateTimesheet" tabindex="-1" role="">
	<div class="modal-dialog modal-login" role="document">
	    <div class="modal-content">
	        <div class="card card-signup card-plain">
	            <div class="modal-body">
	            	<form>
	            		<input type="hidden" id="CallIDTS" name="callid">
	            		<div class="form-group">
					      <label for="inputTitleTS">Title</label>
					      <input type="text" name="title" class="form-control" id="inputTitleTS">
					    </div>
					  <div class="form-row">
					  	<div class="form-group col-md-2">
					      <label for="inputDuration">Dauer</label>
					      <input type="number" step="any" name="duration" class="form-control" id="inputDuration" value="0"/>
					    </div>
					    <div class="form-group col-md-2">
					      <label for="inputPrice">Preis</label>
					      <input type="number" step="any" name="unitprice" class="form-control" id="inputPrice" value="60"/>
					    </div>
					    <div class="form-group col-md-4">
					      <label for="inputCategory">Kategorie</label>
					      <select id="inputCategory" name="category" class="form-control">
					        <?php foreach (Timesheet::Categories() as $key => $Category): ?>
					     		<option value="<?php echo $Category['id']?>" <?php echo ($firstSeen ? "" : "selected")?>><?php echo $Category["name"]?></option>
					     		<?php $firstSeen = true; ?>
					        <?php endforeach ?>
					      </select>
					    </div>
					    <div class="form-group col-md-4">
					      <label for="inputStatusTS">Status</label>
					      <select id="inputStatusTS" name="ts_status" class="form-control">
					        <?php foreach (Timesheet::Config()->status_labels as $key => $status): ?>
					     		<option value="<?php echo $key?>" <?php echo ($firstSeen ? "" : "selected")?>><?php echo $status["name"]?></option>
					     		<?php $firstSeen = true; ?>
					        <?php endforeach ?>
					      </select>
					    </div>
					  </div>
					  <div class="form-group">
					    <label for="inputAddressTS">Adressbuch</label>
					    <input type="text" class="form-control" id="inputAddressTS">
					    <input type="hidden" id="linked_addresses_ts" name="linked_addresses">
					   	<div class="linked_addresses not-selectable"></div>
					  </div>
					  <div class="form-group">
					    <label for="descriptionTS">Beschreibung</label>
					    <textarea type="text" name="description" class="form-control" rows="4" id="descriptionTS"></textarea>
					  </div>
					</form>
					<button class="btn btn-primary">Speichern</button>
	            </div>
	        </div>
	    </div>
	</div>
</div>
<style type="text/css">
	@media only screen and (min-width: 1200px) {
	    .modal-dialog {
	        max-width: 50%!important;
	    }
	}
	.btn {
		padding: 8px 20px;
	}
	.not-selectable {
	  -webkit-touch-callout: none;
	  -webkit-user-select: none;
	  -khtml-user-select: none;
	  -moz-user-select: none;
	  -ms-user-select: none;
	  user-select: none;
	}
	.call_id, .destination {
		cursor: pointer;
	}
	.responsible_users .user,
	.linked_addresses .address {
	    display: flex;
	}

	.responsible_users,
	.linked_addresses {
		padding-top: 4px;
		display: inline-block;
		width: 100%;
	}
	.responsible_users .user,
	.linked_addresses .address {
		border-radius: 5px;
		background: sandybrown;
		float: left;
		margin-right: 5px;
		margin-bottom: 4px;
		padding: 4px;
		cursor: pointer;
	}
	/*.responsible_users .user span.delete {
		display: none;
		color: white;
		margin: 0 0 0 12px;
		font-weight: bold;
		cursor: pointer;
		font-size: 18px;
	}
	.responsible_users .user span.delete {
		display: inline;
	}*/
	.responsible_users .user.active,
	.linked_addresses .address.active {
		background: #008c96;
	}
	.responsible_users .user p,
	.linked_addresses .address p {
		font-size: 12px;
		border-radius: 4px;
		float: left;
		line-height: 2;
		font-weight: 500;
		color: white;
		margin-bottom: 0;
	}
	[data-marked]{
		background: linear-gradient(135deg, #4caf50, #00d486 60%, #00bcd4);
	}
</style>