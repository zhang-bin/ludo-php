<table class="table table-hover">
	<thead>
		<tr>
			<th><?=LG_SHIPPER_NAME?></th>
			<th><?=LG_SHIPPER_COMPANYSHORTNAME?></th>
			<th><?=LG_SHIPPER_COMPANYNAME?></th>
			<th><?=LG_SHIPPER_ADDRESS?></th>
			<th><?=LG_SHIPPER_TEL?></th>
			<th><?=LG_SHIPPER_FAX?></th>
			<th><?=LG_OPERATION?>
		</tr>
	</thead>
	<tbody>
		<?php if (!empty($list)) { foreach ($list as $v) {?>
		<tr>
			<td><?=$v['name']?></td>
			<td><?=$v['companyShortName']?></td>
			<td><?=$v['companyName']?></td>
			<td><?=$v['address']?></td>
			<td><?=$v['telphone']?></td>
			<td><?=$v['fax']?></td>
			<td><a href="<?=url('partsDelivery/delShipper/'.$v['id'])?>" class="btn btn-small btn-warning" name="del" title="<?=LG_PARTS_DELIVERY_SHIPPER_DELETE?>"><?=LG_BTN_DEL?></a></td>
		</tr>
		<?php }}?>
		<?php if (!empty($pager)) { ?><tr><td colspan="20" style="text-align:right;"><?=$pager?></td><?php }?>
	</tbody>
</table>