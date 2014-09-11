<?php
$change = $duplicate ? false : (isset($_GET['id']) ? true : false);
$gTitle = $duplicate ? LG_PARTS_DELIVERY_FACTORY_DUPLICATE : ($change ? LG_PARTS_DELIVERY_FACTORY_CHANGE : LG_PARTS_DELIVERY_FACTORY_ADD);
include tpl('header');
?>
<form name="form1" method="post" id="form1" class="form-horizontal">
	<?php if ($change) {?>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_PARTS_DELIVERY_CODE?></strong></label>
		<div class="controls"><p class="form-control-static"><?=$order['code']?></p></div>
	</div>
	<?php }?>
	<div class="control-group">
		<label class="control-label" for="departureWarehouseId"><strong><?=LG_DEPARTURE_WAREHOUSE?></strong></label>
		<div class="controls"><p class="form-control-static"><?php if ($change) {?>
			<input type="hidden" name="departureWarehouseId" value="<?=$order['departureWarehouseId']?>" />
			<?=$order['departureWarehouse']?></p>
		<?php } else {?>
			<select name="departureWarehouseId" id="departureWarehouseId" class="selectpicker">
				<option value="0"><?=LG_SELECT_CHOOSE?></option>
				<?php foreach ($warehouses as $warehouse) {?>
				<option value="<?=$warehouse['id']?>" <?=($warehouse['id'] == $order['departureWarehouseId']) ? 'selected' : ''?>><?=$warehouse['name']?></option>
				<?php }?>
			</select>
		<?php }?></div>
	</div>
	<div class="control-group">
		<label class="control-label" for="destinationWarehouseId"><strong><?=LG_DESTINATION_WAREHOUSE?></strong></label>
		<div class="controls"><p class="form-control-static"><?php if ($change) {?>
			<input type="hidden" name="destinationWarehouseId" value="<?=$order['destinationWarehouseId']?>" />
			<?=$order['destinationWarehouse']?></p>
		<?php } else {?>
			<select name="destinationWarehouseId" id="destinationWarehouseId" class="selectpicker">
				<option value="0"><?=LG_SELECT_CHOOSE?></option>
				<?php foreach ($warehouses as $warehouse) {?>
				<option value="<?=$warehouse['id']?>" <?=($warehouse['id'] == $order['destinationWarehouseId']) ? 'selected' : ''?>><?=$warehouse['name']?></option>
				<?php }?>
			</select>
		<?php }?></div>
	</div>
	<div class="control-group">
		<label class="control-label" for="shipperId"><strong><?=LG_DESTINATION_SHIPPER?></strong></label>
		<div class="controls">
			<select name="shipperId" id="shipperId" class="selectpicker">
				<option value="0"><?=LG_SELECT_CHOOSE?></option>
				<?php foreach ($shippers as $shipper) {?>
				<option value="<?=$shipper['id']?>" <?=($shipper['id'] == $order['shipperId']) ? 'selected' : ''?>><?=$shipper['name']?></option>
				<?php }?>
			</select>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="consigneeId"><strong><?=LG_DESTINATION_CONSIGNEE?></strong></label>
		<div class="controls">
			<select name="consigneeId" id="consigneeId" class="selectpicker">
				<option value="0"><?=LG_SELECT_CHOOSE?></option>
				<?php foreach ($consignees as $consignee) {?>
				<option value="<?=$consignee['id']?>" <?=($consignee['id'] == $order['consigneeId']) ? 'selected' : ''?>><?=$consignee['name']?></option>
				<?php }?>
			</select>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="shipper"><strong><?=LG_SO_CARRIER?></strong></label>
		<div class="controls"><input type="text" name="shipper" id="shipper" value="<?=$order['shipper']?>" /></div>
	</div>
	<div class="control-group">
		<label class="control-label" for="trackingNum"><strong><?=LG_SO_AWB?></strong></label>
		<div class="controls"><input type="text" name="trackingNum" id="trackingNum" value="<?=$order['trackingNum']?>" /></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_PN?></strong></label>
		<div class="controls">
			<div class="well well-small">
				<p>
					<?=LG_PO_CODE?>:
					<input type="text" id="poCode" style="margin:0;" />
					<input type="button" class="btn btn-primary btn-small" id="searchPN" value="<?=LG_BTN_SEARCH?>" />
				</p>
				<div id="pnResult"></div>
			</div>
			Selected PN:
			<div id="pnSelected">
				<table class="table table-bordered" id="selected">
					<thead>
						<tr>
							<th><?=LG_PN?></th>
							<th><?=LG_PN_QTY?></th>
							<th><?=LG_PN_AOG?></th>
							<th><?=LG_PN_HAS_DELIVERY?></th>
							<th><?=LG_PN_DELIVERY?></th>
							<th><?=LG_OPERATION?></th>
						</tr>
					</thead>
					<tbody>
						<?php if (!empty($details)) {foreach ($details as $detail) {?>
						<tr rel="<?=$detail['purchaseOrderDetailId']?>">
							<td><?=$detail['pn']?></td>
							<td><?=$detail['qty']?></td>
							<td><?=$detail['aog']?></td>
							<td><?=$detail['delivery']?></td>
							<td><input type="text" name="delivery[<?=$detail['purchaseOrderDetailId']?>]" value="<?=$detail['deliveryQty']?>" /></td>
							<td><a class="del btn"><?=LG_BTN_DEL?></a></td>
						</tr>
						<?php }}?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<input type="hidden" id="id" name="id" value="<?=$order['id']?>"/>
			<input id="saveBtn" type="button" value="<?=LG_BTN_SAVE1?>" class="btn btn-success" />
			<input id="submitBtn" type="button" value="<?=LG_BTN_SAVE?>" class="btn btn-primary" />
			<input type="button" onclick="javascript:history.back();" value="<?=LG_BTN_CANCEL?>" class="btn" />
		</div>
	</div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	$("#searchPN").click(function(){
		$("#pnResult").loading("<?=url('partsDelivery/searchPN')?>", {"poCode": $("#poCode").val()});
		return false;
	});
	$("#pnResult").on("click", "a.p", function(){
		$("#pnResult").loading(this.href, {"poCode": $("#poCode").val()});
		return false;
	})
	$("#pnResult").on("click", ".checkOne", function(){
		$(this).checkPN();
	});
	$("#pnResult").on("click", ".checkAll", function(){
		if ($(this).prop("checked")) {
			$(".checkOne").prop("checked", true);
		} else {
			$(".checkOne").prop("checked", false);
		}
		$(".checkOne").checkPN();
	});
	$("#pnSelected").on("click", ".del", function(){
		$(this).parent().parent().remove();
		return false;
	});
	$.fn.checkPN = function(){
		$(this).each(function(){
			var uid = $(this).attr("uid");
			if ($(this).prop("checked")) {
				if ($("tr[rel="+uid+"]").length <= 0) {
					var tr = $("<tr rel='"+uid+"'></tr>");
					var clone = $(this).parent().parent();
					var aog = clone.find(".aog").text();
					var delivery = clone.find(".delivery").text();
					
					tr.append("<td>"+clone.find(".pn").text()+"</td>");
					tr.append("<td>"+clone.find(".qty").text()+"</td>");
					tr.append("<td>"+aog+"</td>");
					tr.append("<td>"+delivery+"</td>");
					tr.append("<td><input type='text' name='delivery["+uid+"]' value='"+(aog-delivery)+"' /></td>");
					tr.append("<td><a class='del btn'></a></td>");
					$("#selected").append(tr);
				}
			} else {
				$("tr[rel="+uid+"]").remove();
			}
		});
	}

	$('#submitBtn').click(function(){
		$(this).attr("disabled", "disabled");
		$.posting("<?=url('partsDelivery/factoryAdd')?>", $("#form1").serialize(), function(result) {
			$("#submitBtn").removeAttr("disabled");
			if (ajaxHandler(result)) return false;
			return false;
		}); 
		return false;
	});

	$("#saveBtn").click(function(){
		$(this).attr("disabled", "disabled");
		$.posting("<?=url('partsDelivery/factorySave')?>", $("#form1").serialize(), function(result) {
			$("#saveBtn").removeAttr("disabled");
			if (ajaxHandler(result)) return false;
			return false;
		});
		return false;
	});
});
</script>
<?php include tpl('footer');?>