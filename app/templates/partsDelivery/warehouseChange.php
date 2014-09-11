<?php
$change = $duplicate ? false : (isset($_GET['id']) ? true : false);
$gTitle = $duplicate ? LG_PARTS_DELIVERY_WAREHOUSE_DUPLICATE : ($change ? LG_PARTS_DELIVERY_WAREHOUSE_CHANGE : LG_PARTS_DELIVERY_WAREHOUSE_ADD);
include tpl('header');
Load::js('autocomplete');
?>
<style>
td span{color:#808080;}
</style>

<form id="form1" method="post" name="form1" class="form-horizontal">
	<?php if ($change) {?>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_WAREHOUSE_CODE?></strong></label>
		<div class="controls"><p class="form-control-static"><?=$order['code']?></p></div>
	</div>
	<?php }?>
	<div class="control-group">
		<label class="control-label" for="departureWarehouseId"><strong><?=LG_WAREHOUSE_FROM?></strong></label>
		<div class="controls">			
			<?php if($change){?>
				<span><?=$order['departureWarehouse']?></span>
				<input type="hidden" value="<?=$order['departureWarehouseId']?>" name="departureWarehouseId" >
			<?php } else {?>
				<select name="departureWarehouseId" id="departureWarehouseId" class="selectpicker">
				<option value="0"><?=LG_SELECT_CHOOSE?></option>
				<?php foreach($fromWarehouses as $warehouse) { ?>
	           		<option value="<?=$warehouse['id']?>" <?=($warehouse['id'] == $order['departureWarehouseId']) ? 'selected' : ''?> ><?=$warehouse['name']?></option>
			  	<?php }?>
				</select>
			<?php }?>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="destinationWarehouseId"><strong><?=LG_WAREHOUSE_TO?></strong></label>
		<div class="controls">			
			<?php if($change){?>
				<span><?=$order['destinationWarehouse']?></span>
				<input type="hidden" value="<?=$order['destinationWarehouseId']?>" name="destinationWarehouseId" >
			<?php } else {?>
				<select name="destinationWarehouseId" id="destinationWarehouseId" class="selectpicker">
				<option value="0"><?=LG_SELECT_CHOOSE?></option>
				<?php foreach($toWarehouses as $warehouse) { ?>
	           		<option value="<?=$warehouse['id']?>" <?=($warehouse['id'] == $order['destinationWarehouseId']) ? 'selected' : ''?> ><?=$warehouse['name']?></option>
			  	<?php }?>
				</select>
			<?php }?>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong>Parts</strong></label>
		<div class="controls">
			<?php if (!empty($details)) {foreach ($details as $detail) {?>
				<div style="margin-bottom:10px;" class="pnDiv">
					PN:<input type="text" size="25" name="pn[]" value="<?=$detail['pn']?>" />
					QTY:<input type="text" size=25 name="qty[]" value="<?=$detail['deliveryQty']?>" />
					<span name="part"></span>
					<span name="desc"></span>
				</div>
				<?php }
			} else {?>
				<div style="margin-bottom:10px;" class="pnDiv">
					PN:<input type="text" size="25" name="pn[]" />
					QTY:<input type="text" size=25 name="qty[]" />
					<span name="part"></span>
					<span name="desc"></span>
				</div>
			<?php }?>
			<div style="margin-bottom:10px;" class="pnDiv">
				PN:<input type="text" size=25 name="pn[]" />
				QTY:<input type="text" size=25 name="qty[]" />
				<span name="part"></span>
				<span name="desc"></span>
			</div>
			<div id="pnSpan"></div>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="consigneeId"><strong><?=LG_WAREHOUSE_INVOICECONSIGNEE?></strong></label>
		<div class="controls">
			<select name="consigneeId" id="consigneeId" class="selectpicker">
				<option value="0"><?=LG_SELECT_CHOOSE?></option>
				<?php foreach ($consignees as $v){?>
				<option value = "<?=$v['id']?>" <?=($v['id'] == $order['consigneeId']) ? 'selected' : ''?> ><?=$v['name']?></option>
				<?php }?>
			</select>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="shipperId"><strong><?=LG_WAREHOUSE_INVOICESHIPPER?></strong></label>
		<div class="controls">
			<select name="shipperId" id="shipperId" class="selectpicker">
				<option value="0"><?=LG_SELECT_CHOOSE?></option>
				<?php foreach ($shippers as $v){?>
				<option value = "<?=$v['id']?>" <?=($v['id'] == $order['shipperId']) ? 'selected' : ''?>><?=$v['name']?></option>
				<?php }?>
			</select>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="shipper"><strong><?=LG_WAREHOUSE_SHIPPER?></strong></label>
		<div class="controls">
			<input type="text" name="shipper"  id="shipper" value="<?=$order['shipper']?>" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="trackingNum"><strong><?=LG_WAREHOUSE_TRACKING_NUMBER?></strong></label>
		<div class="controls">
			<input type="text" name="trackingNum"  id="trackingNum" value="<?=$order['trackingNum']?>" />
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<input name="id" type="hidden" value="<?=$order['id']?>">
			<input id="saveBtn" type="button" value="<?=LG_BTN_SAVE1?>" class="btn btn-success" />
			<input id="submitBtn" type="button" value="<?=LG_BTN_SAVE?>" class="btn btn-primary" />
			<input type="button" onclick="javascript:history.back();" value="<?=LG_BTN_CANCEL?>" class="btn" />
		</div>
	</div>
</form>
<script type="text/javascript">
$(document).ready(function() {
	var isOpen = false;
	$("[name='pn[]']").live("focus", function(){
		var i = $(this);
		var div = i.parent("div");
		var num = div.nextAll("div.pnDiv").length;
		if (num == 0) div.after(div.clone());
		i.autocomplete({
			serviceUrl: "<?=url('shippingOrder/suggestParts')?>"+"?warehouseId="+$("#departureWarehouseId").val(),
			onSelect: function(suggest){
				$(this).val(suggest.data);
				i.nextAll("span[name='desc']").text(suggest.en);
			}
		});
	});

	$("[name='qty[]']").live("blur", function(){
		var pn = $(this).prevAll('input').val();
		if (pn == "") return;
		var i = $(this);
		if (i.val() == "") return;
		$.post("<?=url('shippingOrder/checkOrderQty')?>", {"qty":i.val(), "pn":pn, "warehouseId":$("#departureWarehouseId").val()}, function(result){
			switch (result) {
				case '0':
					i.nextAll('span[name=part]').html('<font color="red">No enough stock</color>');
					break;
				case '1':
					i.nextAll('span[name=part]').html('<font color="green">OK!</color>');
					break;
				case '2':
					break;
					i.nextAll('span[name=part]').html('<font color="red">part not exist</color>');
				case '3':
					i.nextAll('span[name=part]').html('<font color="red">please enter qty</color>');
					break;
				default:
					break;
			}
		});
	});
	
	$('#saveBtn').click(function(){
		$(this).attr("disabled", "disabled");
		$.post("<?= url('partsDelivery/warehouseSave')?>", $("#form1").serialize(), function(result) {
			$("#saveBtn").removeAttr("disabled");
			if(ajaxHandler(result)) return;
		});
		return false;
	});
	
	$('#submitBtn').click(function(){
		$(this).attr("disabled", "disabled");
		$.post("<?= url('partsDelivery/warehouseAdd')?>", $("#form1").serialize(), function(result) {
			$("#submitBtn").removeAttr("disabled");
			if(ajaxHandler(result)) return;
		});
		return false;
	});
});
</script>
<?php include tpl('footer')?>