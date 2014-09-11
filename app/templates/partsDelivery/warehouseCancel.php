<?php
$gTitle = LG_PARTS_DELIVERY_WAREHOUSE_CANCEL;
include tpl('header');
?>
<form class="form form-horizontal" method="post" action="<?=url('partsDelivery/warehouseCancel')?>">
	<div class="control-group">
		<label class="control-label"><strong><?=LG_WAREHOUSE_CODE?></strong></label>
		<div class="controls"><?=$order['code']?></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_WAREHOUSE_FROM?></strong></label>
		<div class="controls"><?=$order['departureWarehouse']?></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_WAREHOUSE_TO?></strong></label>
		<div class="controls"><?=$order['destinationWarehouse']?></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_WAREHOUSE_INVOICECONSIGNEE?></strong></label>
		<div class="controls"><?=$order['shipperName']?></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_WAREHOUSE_INVOICESHIPPER?></strong></label>
		<div class="controls"><?=$order['consigneeName']?></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_WAREHOUSE_SHIPPER?></strong></label>
		<div class="controls"><?=$order['shipper']?></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_WAREHOUSE_TRACKING_NUMBER?></strong></label>
		<div class="controls"><?=$order['trackingNum']?></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_PN?></strong></label>
		<div class="controls">
			<table class="table table-hover" id="selected">
				<thead>
					<tr>
						<th><?=LG_PN?></th>
						<th><?=LG_PN_QTY?></th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($details)) {foreach ($details as $detail) {?>
					<tr>
						<td><?=$detail['pn']?></td>
						<td><?=$detail['deliveryQty']?></td>
					</tr>
					<?php }}?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_CANCEL_REASON?></strong></label>
		<div class="controls">
			<textarea name="cancelReason" rows="3" class="span4"></textarea>
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<input type="hidden" id="id" name="id" value="<?=$order['id']?>"/>
			<input id="submitBtn" type="submit" value="<?=LG_BTN_SAVE?>" class="btn btn-primary" />
			<input type="button" onclick="javascript:history.back();" value="<?=LG_BTN_CANCEL?>" class="btn" />
		</div>
	</div>
</form>
<?php include tpl('footer');?>