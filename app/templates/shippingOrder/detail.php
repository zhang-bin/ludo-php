<?php
$gTitle = LG_SHIPPINGORDER_INFO;
include tpl('header');
?>
<div class="form-horizontal">
	<div class="control-group">
		<label class="control-label"><strong>User</strong></label>
		<div class="controls"><p class="form-control-static"><?=$order['nickname']?></p></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_SHIPPINGORDER_FROM?></strong></label>
		<div class="controls"><p class="form-control-static"><?=$order['departureName']?></p></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_SHIPPINGORDER_TO?></strong></label>
		<div class="controls"><p class="form-control-static"><?=$order['destName']?></p></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong>Parts Info</strong></label>
		<div class="controls">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>New PN</th>
						<th>Order PN</th>
						<th>Old PN</th>
						<th>QTY</th>
						<th>Discrepancy</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($orderDetail as $detail) {?>
					<tr>
						<td><?=$detail['partsPN']?></td>
						<td><?=$detail['partsPN2']?></td>
						<td><?=$detail['partsPN3']?></td>
						<td><?=$detail['qty']?></td>
						<td><?php if (!empty($detail['num'])) {echo (($detail['status'] == 1) ? '+' : '-').$detail['num'];} else { echo 0;}?></td>
					</tr>
					<?php }?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_SHIPPINGORDER_ARRIVE_TIME?></strong></label>
		<div class="controls"><p class="form-control-static"><?=getCurrentTime($order['arrivedTime'])?></p></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_SHIPPINGORDER_STATUS?></strong></label>
		<div class="controls"><p class="form-control-static"><?=$param['status'][$order['status']]?></p></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_SHIPPINGORDER_SARMA?></strong></label>
		<div class="controls"><p class="form-control-static"><?=$order['sanumber']?></p></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_SHIPPINGORDER_SHIPPER?></strong></label>
		<div class="controls"><p class="form-control-static"><?=$order['shipper']?></p></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_SHIPPINGORDER_TRACKING_NUMBER?></strong></label>
		<div class="controls"><p class="form-control-static"><?=$order['trackingNum']?></p></div>
	</div>
</div>
<?php include tpl('footer')?>