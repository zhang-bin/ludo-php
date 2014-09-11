<table class="table table-hover">
	<thead>
		<tr>
			<th><?=LG_WARNING_PN?></th>
			<th><?=LG_WARNING_ABC_CLASS?></th>
			<th><?=LG_WARNING_GROUP?></th>
			<th><?=LG_WARNING_CATEGORY?></th>
			<th><?=LG_WARNING_MODEL?></th>
			<th><?=LG_WARNING_MODEL_WARRANTY?></th>
			<th><?=LG_WARNING_MODEL_WARRANTY_BEGIN_TIME?></th>
			<th><?=LG_WARNING_MODEL_WARRANTY_END_TIME?></th>
			<th><?=LG_WARNING_FCST_DEMAND?></th>
			<th><?=LG_WARNING_INVENTORY?></th>
			<th><?=LG_WARNING_SHIPPING_ORDER?></th>
			<th><?=LG_WARNING_PARTS_APPLY?></th>
			<th><?=LG_WARNING_VALUE?>(not Include Purchase)</th>
			<th><?=LG_WARNING_VALUE?>(Include Purchase)</th>
			<th><?=LG_WARNING_ON_WAY?></th>
			<th><?=LG_WARNING_ON_ORDER?></th>
		</tr>
	</thead>
	<tbody>
		<?php if (!empty($warnings)) { foreach ($warnings as $warning) {?>
		<tr>
			<td><?=$warning['pn']?></td>	
			<td><?=$warning['abcClass']?></td>	
			<td><?=$warning['group']?></td>
			<td><?=$warning['category']?></td>
			<td><?=$warning['model']?></td>
			<td><?=$warning['warranty']?></td>
			<td><?=$warning['beginTime']?></td>
			<td><?=$warning['endTime']?></td>
			<td><?=$warning['fcst']?></td>
			<td><?=$warning['inventory']?></td>
			<td><?=$warning['shipping']?></td>
			<td><?=$warning['apply']?></td>
			<td><?=$warning['notInclude']?></td>
			<td><?=$warning['include']?></td>
			<td><?=$warning['onWay']?></td>
			<td><?=$warning['onOrder']?></td>
		</tr>
		<?php }}?>
		<?php if (!empty($pager)) { ?><tr><td colspan="30" style="text-align:right;"><?=$pager?></td><?php }?>
	</tbody>
</table>