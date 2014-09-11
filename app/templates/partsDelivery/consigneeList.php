<table class="table table-hover">
	<thead>
		<tr>
			<th><?=LG_CONSIGNEE_NAME?></th>
			<th><?=LG_CONSIGNEE_COMPANY?></th>
			<th><?=LG_CONSIGNEE_ADDRESS?></th>
			<th><?=LG_CONSIGNEE_CONTACT?></th>
			<th><?=LG_CONSIGNEE_TEL?></th>
			<th><?=LG_CONSIGNEE_FAX?></th>
			<th><?=LG_CONSIGNEE_SHIPMENTFROM?></th>
			<th><?=LG_CONSIGNEE_TRANSTO?></th>
			<th><?=LG_CONSIGNEE_PRICETERM?></th>
			<th><?=LG_OPERATION?></th>
		</tr>
	</thead>
	<tbody>
		<?php if (!empty($list)) { foreach ($list as $v) {?>
		<tr>
			<td><?=$v['name']?></a></td>
			<td><?=$v['company']?></td>
			<td><?=$v['address']?></td>
			<td><?=$v['contact']?></td>
			<td><?=$v['tel']?></td>
			<td><?=$v['fax']?></td>
			<td><?=$v['shipmentFrom']?></td>
			<td><?=$v['transTo']?></td>
			<td><?=$v['priceTerm']?></td>
			<td><a href="<?=url('partsDelivery/delConsignee/'.$v['id'])?>" name="del" class="btn btn-warning btn-small" title="<?=LG_PARTS_DELIVERY_CONSIGNEE_DELETE?>"><?=LG_BTN_DEL?></a></td>
		</tr>
		<?php }}?>
		<?php if (!empty($pager)) { ?><tr><td colspan="20" style="text-align:right;"><?=$pager?></td><?php }?>
	</tbody>
</table>