<?php 
$conf = Load::conf('PartsDelivery');
$conf = $conf['factory'];
?>
<table class="table table-hover">
	<thead>
	<tr>
		<th><?=LG_PARTS_DELIVERY_CODE?></th>
		<th><?=LG_DEPARTURE_WAREHOUSE?></th>
		<th><?=LG_DESTINATION_WAREHOUSE?></th>
		<th><?=LG_SO_CODE?></th>
		<th><?=LG_SO_CARRIER?></th>
		<th><?=LG_SO_AWB?></th>
		<th><?=LG_PARTS_DELIVERY_STATUS?></th>
		<th><?=LG_CREATE_TIME?></th>
		<th><?=LG_OPERATION?></th>
	</tr>
	</thead>
	<tbody>
	<?php
	if (!empty($list)) { foreach ($list as $v) {
	?>
	<tr>
		<td><?=$v['code']?></td>
		<td><?=$v['departureWarehouse']?></td>
		<td><?=$v['destinationWarehouse']?></td>
		<td><a href="<?=url('shippingOrder/view/'.$v['shippingOrderId'])?>" target="_blank"><?=$v['shippingOrderCode']?></a></td>
		<td><?=$v['shipper']?></td>
		<td><?=$v['trackingNum']?></td>
		<td><?=$conf['status'][$v['status']]?></td>
		<td><?=$v['createTime']?></td>
		<td>
			<div class="btn-group">
			  	<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
			  	<ul class="dropdown-menu pull-right">
					<?php if ($v['status'] == PartsDelivery::STATUS_FACTORY_PROCESS) {?>
					<li><a href="<?=url('partsDelivery/factoryChange/'.$v['id'])?>"><i class="icon-edit"></i> <?=LG_BTN_EDIT?></a></li>
					<li><a href="<?=url('partsDelivery/factoryDel/'.$v['id'])?>" name="del" title="<?=LG_PARTS_DELIVERY_FACTORY_DELETE?>"><i class="icon-trash"></i> <?=LG_BTN_DEL?></a></li>
					<?php }?>
					<?php if ($v['status'] == PartsDelivery::STATUS_FACTORY_SUBMIT) {?>
					<li><a href="<?=url('partsDelivery/factoryCancel/'.$v['id'])?>"><i class="icon-remove"></i> <?=LG_BTN_CANCEL?></a></li>
					<li><a href="<?=url('partsDelivery/factoryClose/'.$v['id'])?>"><i class="icon-off"></i> <?=LG_BTN_CLOSE?></a></li>
					<li><a href="<?=url('partsDelivery/factoryInvoice/'.$v['id'])?>" target="_blank"><i class="icon-print"></i> Invoice</a></li>
					<?php }?>
					<?php if ($v['status'] == PartsDelivery::STATUS_FACTORY_CLOSE) {?>
					<li><a href="<?=url('partsDelivery/factoryInvoice/'.$v['id'])?>" target="_blank"><i class="icon-print"></i> Invoice</a></li>
					<?php }?>
					<li><a href="<?=url('partsDelivery/factoryDuplicate/'.$v['id'])?>"><i class="icon-copy"></i> Duplicate</a></li>
			  	</ul>
			</div>
		</td>
	</tr>
	<?php }}?>
	<?php if (!empty($pager)) { ?><tr><td colspan="20" style="text-align:right;"><?=$pager?></td><?php }?>
	</tbody>
</table>