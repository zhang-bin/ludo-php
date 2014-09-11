<?php
$change = isset($_GET['id']) ? true : false;
$gTitle = $change ? LG_PARTS_DELIVERY_SHIPPER_CHANGE : LG_PARTS_DELIVERY_SHIPPER_ADD;
include tpl('header');
?>
<form class="form form-horizontal" method="post" action="<?=url('partsDelivery/addShipper')?>">
	<div class="control-group">
		<label class="control-label" for="name"><strong><?=LG_SHIPPER_NAME?></strong></label>
		<div class="controls"><input name="name" id="name" /></div>
	</div>
	<div class="control-group">
		<label class="control-label" for="shortName"><strong><?=LG_SHIPPER_COMPANYSHORTNAME?></strong></label>
		<div class="controls"><input name="shortName" id="shortName" /></div>
	</div>
	<div class="control-group">
		<label class="control-label" for="companyName"><strong><?=LG_SHIPPER_COMPANYNAME?></strong></label>
		<div class="controls"><input name="companyName" id="companyName" /></div>
	</div>
	<div class="control-group">
		<label class="control-label" for="address"><strong><?=LG_SHIPPER_ADDRESS?></strong></label>
		<div class="controls"><input name="address" id="address" /></div>
	</div>
	<div class="control-group">
		<label class="control-label" for="tel"><strong><?=LG_SHIPPER_TEL?></strong></label>
		<div class="controls"><input name="tel" id="tel" /></div>
	</div>
	<div class="control-group">
		<label class="control-label" for="fax"><strong><?=LG_SHIPPER_FAX?></strong></label>
		<div class="controls"><input name="fax" id="fax" /></div>
	</div>
	<div class="control-group">
		<div class="controls">
			<input id="submitBtn" type="submit" value="<?=LG_BTN_SAVE?>" class="btn btn-primary" />
			<a href="javascript:history.back();" class="btn"><?=LG_BTN_CANCEL?></a>
		</div>
	</div>
</form>
<?php include tpl('footer')?>