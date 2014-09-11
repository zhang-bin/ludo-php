<?php
$gTitle = LG_PARTS_DELIVERY_FACTORY_CLOSE;
include tpl('header');
?>
<form class="form form-horizontal" method="post" action="<?=url('partsDelivery/factoryClose')?>">
	<div class="control-group">
		<label class="control-label"><strong><?=LG_PARTS_DELIVERY_CODE?></strong></label>
		<div class="controls"><?=$order['code']?></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_DEPARTURE_WAREHOUSE?></strong></label>
		<div class="controls"><?=$order['departureWarehouse']?></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_DESTINATION_WAREHOUSE?></strong></label>
		<div class="controls"><?=$order['destinationWarehouse']?></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_DESTINATION_SHIPPER?></strong></label>
		<div class="controls"><?=$order['shipperName']?></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_DESTINATION_CONSIGNEE?></strong></label>
		<div class="controls"><?=$order['consigneeName']?></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_SO_CARRIER?></strong></label>
		<div class="controls"><?=$order['shipper']?></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_SO_AWB?></strong></label>
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
						<th><?=LG_PN_AOG?></th>
						<th><?=LG_PN_HAS_DELIVERY?></th>
						<th><?=LG_PN_DELIVERY?></th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($details)) {foreach ($details as $detail) {?>
					<tr>
						<td><?=$detail['pn']?></td>
						<td><?=$detail['qty']?></td>
						<td><?=$detail['aog']?></td>
						<td><?=$detail['delivery']?></td>
						<td><?=$detail['deliveryQty']?></td>
					<?php }}?>
				</tbody>
			</table>
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