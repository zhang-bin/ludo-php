<?php
$gTitle = isset($_GET['id']) ? LG_PO_CHANGE : LG_PO_ADD;
include tpl('header');
$conf = Load::conf('PurchaseOrder');
Load::js('bootstrap-datetimepicker');
?>
<form method="post" class="form form-horizontal" action="<?=url('purchaseOrder/save')?>">
	<div class="control-group">
		<label class="control-label"><strong><?=LG_PO_CODE?></strong></label>
		<div class="controls">
			<input type="text" name="code" value="<?=$order['code']?>" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="supplierId"><strong><?=LG_PO_SUPPLIER?></strong></label>
		<div class="controls">
			<select name="supplierId" id="supplierId" class="selectpicker" data-live-search="true">
				<option value="0"><?=LG_SELECT_CHOOSE?></option>
				<?php foreach ($suppliers as $supplier) {?>
				<option value="<?=$supplier['id']?>" <?=($supplier['id'] == $order['supplierId']) ? 'selected' : ''?>><?=$supplier['supplier']?></option>
				<?php }?>
			</select>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="warehouseId"><strong><?=LG_PO_WAREHOUSE?></strong></label>
		<div class="controls">
			<select name="warehouseId" id="warehouseId" class="selectpicker" data-live-search="true">
				<option value="0"><?=LG_SELECT_CHOOSE?></option>
				<?php foreach ($warehouses as $warehouse) {?>
				<option value="<?=$warehouse['id']?>" <?=($warehouse['id'] == $order['warehouseId']) ? 'selected' : ''?>><?=$warehouse['name']?></option>
				<?php }?>
			</select>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="type"><strong><?=LG_PO_TYPE?></strong></label>
		<div class="controls">
			<select name="type" id="type" class="selectpicker">
				<option value="0"><?=LG_SELECT_CHOOSE?></option>
				<?php foreach ($conf['type'] as $k => $type) {?>
				<option value="<?=$k?>" <?=($k == $order['type']) ? 'selected' : ''?>><?=$type.'--'.$k?></option>
				<?php }?>
			</select>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="demandTime"><strong><?=LG_PO_DEMAND_TIME?></strong></label>
		<div class="controls">
			<input name="demandTime" id="demandTime" value="<?=$order['demandTime']?>" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="currency"><strong><?=LG_PO_CURRENCY?></strong></label>
		<div class="controls">
			<select name="currency" id="currency" class="selectpicker">
				<option value="0"><?=LG_SELECT_CHOOSE?></option>
				<?php foreach ($conf['currency'] as $currency) {?>
				<option value="<?=$currency?>" <?=($currency == $order['currency']) ? 'selected' : ''?>><?=$currency?></option>
				<?php }?>
			</select>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="warrnaty"><strong><?=LG_PO_WARRANTY?></strong></label>
		<div class="controls">
			<select name="warranty" id="warranty" class="selectpicker">
				<option value="0"><?=LG_SELECT_CHOOSE?></option>
				<?php foreach ($conf['warranty'] as $k => $warranty) {?>
				<option value="<?=$k?>" <?=($k == $order['warranty']) ? 'selected' : ''?>><?=$warranty?></option>
				<?php }?>
			</select>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="remark"><strong><?=LG_PO_REMARK?></strong></label>
		<div class="controls">
			<textarea name="remark" id="remark" rows="4" class="span5"><?=$order['remark']?></textarea>
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<input type="hidden" id="id" name="id" value="<?=$order['id']?>"/>
			<input id="submitBtn" type="submit" value="<?=LG_BTN_SAVE1?>" class="btn btn-success" />
			<input type="button" onclick="javascript:history.back();" value="<?=LG_BTN_CANCEL?>" class="btn" />
		</div>
	</div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	$("#demandTime").datetimepicker({
		startView: 2,
		minView: 2,
        maxView: 2,
        format: 'yyyy-mm-dd',
		autoclose: 1
	});
		
});
</script>
<?php include tpl('footer');?>