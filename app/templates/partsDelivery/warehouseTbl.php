<?php 
$conf = Load::conf('PartsDelivery');
$conf = $conf['warehouse'];
?>
<table class="table table-hover">
	<thead>
		<tr>
			<th><?=LG_WAREHOUSE_CODE?></th>
			<th><?=LG_WAREHOUSE_FROM?></th>
			<th><?=LG_WAREHOUSE_TO?></th>
			<th><?=LG_WAREHOUSE_SHIPPER?></th>
			<th><?=LG_WAREHOUSE_TRACKING_NUMBER?></th>
			<th><?=LG_WAREHOUSE_SHIPPINGORDER?></th>
			<th><?=LG_PARTS_DELIVERY_STATUS?></th>
			<th><?=LG_OPERATION?></th>
		</tr>
	</thead>
	<tbody>
		<?php if (!empty($list)) { foreach ($list as $v) {?>
		<tr>
			<td><?=$v['code']?></td>
			<td><?=$v['departureWarehouse']?></td>
			<td><?=$v['destinationWarehouse']?></td>
			<td><?=$v['shipper']?></td>
			<td><?=$v['trackingNum']?></td>
			<td><a href="<?=url('shippingOrder/view/'.$v['shippingOrderId'])?>" target="_blank" ><?=$v['shippingOrderCode']?></a></td>
			<td><?=$conf['status'][$v['status']]?></td>
			<td>
				<div class="btn-group">
				  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
				    Action <span class="caret"></span>
				  </button>
				  <ul class="dropdown-menu pull-right">
					<?php if ($v['status'] == PartsDelivery::STATUS_WAREHOUSE_PROCESS) {?>
					<li><a href="<?=url('partsDelivery/warehouseChange/'.$v['id'])?>"><i class="icon-edit"></i> <?=LG_BTN_EDIT?></a></li>
					<li><a href="<?=url('partsDelivery/warehouseDel/'.$v['id'])?>" name="del" title="<?=LG_PARTS_DELIVERY_WAREHOUSE_DELETE?>"><i class="icon-trash"></i> <?=LG_BTN_DEL?></a></li>
					<?php }?>
					<?php if ($v['status'] == PartsDelivery::STATUS_WAREHOUSE_SUBMIT) {?>
					<li><a href="<?=url('partsDelivery/warehouseCancel/'.$v['id'])?>"><i class="icon-remove"></i> <?=LG_BTN_CANCEL?></a></li>
					<li><a href="<?=url('partsDelivery/warehouseClose/'.$v['id'])?>"><i class="icon-off"></i> <?=LG_BTN_CLOSE?></a></li>
					<li><a href="<?=url('partsDelivery/warehouseInvoice/'.$v['id'])?>" target="_blank"><i class="icon-print"></i> Invoice</a></li>
					<?php }?>
					<?php if ($v['status'] == PartsDelivery::STATUS_WAREHOUSE_CLOSE) {?>
					<li><a href="<?=url('partsDelivery/warehouseInvoice/'.$v['id'])?>" target="_blank"><i class="icon-print"></i> Invoice</a></li>
					<?php }?>
					<li><a href="<?=url('partsDelivery/warehouseDuplicate/'.$v['id'])?>"><i class="icon-copy"></i> Duplicate</a></li>
				  </ul>
				</div>
			</td>
		</tr>
		<?php }}?>
		<?php if (!empty($pager)) { ?><tr><td colspan="20" style="text-align:right;"><?=$pager?></td><?php }?>
	</tbody>
</table>