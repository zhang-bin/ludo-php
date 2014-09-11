<?php
$change = isset($_GET['id']) ? true : false;
$gTitle = $change ? LG_PARTS_DELIVERY_CONSIGNEE_CHANGE : LG_PARTS_DELIVERY_CONSIGNEE_ADD;
include tpl('header');
?>
<form method="post" class="form form-horizontal" action="<?=url('partsDelivery/addConsignee')?>">
	<div class="control-group">
		<label class="control-label" for="name"><strong><?=LG_CONSIGNEE_NAME?></strong></label>
		<div class="controls"><input name="name" id="name" /></div>
	</div>
	<div class="control-group">
		<label class="control-label" for="company"><strong><?=LG_CONSIGNEE_COMPANY?></strong></label>
		<div class="controls"><input name="company" id="company" /></div>
	</div>
	<div class="control-group">
		<label class="control-label" for="address"><strong><?=LG_CONSIGNEE_ADDRESS?></strong></label>
		<div class="controls"><input name="address" id="address" /></div>
	</div>
	<div class="control-group">
		<label class="control-label" for="contact"><strong><?=LG_CONSIGNEE_CONTACT?></strong></label>
		<div class="controls"><input name="contact" id="contact" /></div>
	</div>
	<div class="control-group">
		<label class="control-label" for="tel"><strong><?=LG_CONSIGNEE_TEL?></strong></label>
		<div class="controls"><input name="tel" id="tel" /></div>
	</div>
	<div class="control-group">
		<label class="control-label" for="fax"><strong><?=LG_CONSIGNEE_FAX?></strong></label>
		<div class="controls"><input name="fax" id="fax" /></div>
	</div>
	<div class="control-group">
		<label class="control-label" for="shipmentfrom"><strong><?=LG_CONSIGNEE_SHIPMENTFROM?></strong></label>
		<div class="controls"><input name="shipmentfrom" id="shipmentfrom" /></div>
	</div>
	<div class="control-group">
		<label class="control-label" for="transto"><strong><?=LG_CONSIGNEE_TRANSTO?></strong></label>
		<div class="controls"><input name="transto" id="transto" /></div>
	</div>
	<div class="control-group">
		<label class="control-label" for="priceterm"><strong><?=LG_CONSIGNEE_PRICETERM?></strong></label>
		<div class="controls"><input name="priceterm" id="priceterm" /></div>
	</div>
	<div class="control-group">
		<div class="controls">
			<input id="submitBtn" type="submit" value="<?=LG_BTN_SAVE?>" class="btn btn-primary" />
			<a href="javascript:history.back();" class="btn"><?=LG_BTN_CANCEL?></a>
		</div>
	</div>
</form>
<?php include tpl('footer')?>