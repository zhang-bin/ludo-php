<style>
.even{
	background-color:#ccc;
}
.odd{
	background-color:#aaa;	
}
</style>
<table class="table table-hover" width="100%">
	<thead>
		<tr>
			<th><?=LG_FAILURE_RATE_MODEL?></th>
			<th><?=LG_FAILURE_RATE_CATEGORY?></th>
			<th><?=LG_FAILURE_RATE_MONTH?></th>
			<th><?=LG_FAILURE_RATE_SALESTIME?></th>
			<th><?=LG_FAILURE_RATE_QTY?></th>
			<th><?=LG_FAILURE_RATE_WARRANTY?></th>
			<th><?=LG_FAILURE_RATE_RATE?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($shipments as $shipment) {?>
		<tr>
			<td><?=$shipment['model']?></td>
			<td><?=$shipment['category']?></td>
			<td><?=$shipment['month']?></td>
			<td><?=$shipment['salesTime']?></td>
			<td><?=$shipment['qty']?></td>
			<td><?=$shipment['warranty']?></td>
			<td><?=$shipment['rate']?></td>
		</tr>
		<?php }?>
		<tr>
			<?php if (!empty($pager)) {?><td style="text-align:right;" colspan="20" ><?=$pager?></td><?php }?>
		</tr>
	</tbody>
</table>