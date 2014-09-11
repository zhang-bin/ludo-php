<table class="table table-hover">
	<thead>
		<tr>
			<th><?=LG_FAILURE_RATE_MODEL?></th>
			<th><?=LG_FAILURE_RATE_CATEGORY?></th>
            <th><?=LG_FAILURE_RATE_MONTH?></th>
            <th><?=LG_FAILURE_RATE_COUNTRY?></th>
            <th><?=LG_FAILURE_RATE_QTY?></th>
            <th><?=LG_FAILURE_RATE_WARRANTY?></th>
            <th><?=LG_FAILURE_RATE_RATE?></th>
		</tr>
	</thead>
	<tbody>
		<?php if (!empty($models)) {foreach ($models as $model) {?>
		<tr>
			<td><?=$model['model']?></td>
			<td><?=$model['category']?></td>
			<td><?=$model['month']?></td>
			<td><?=$model['country']?></td>
			<td><?=$model['qty']?></td>
			<td><?=$model['warranty']?></td>
			<td><?=$model['rate']?></td>
		</tr>
		<?php }}?>	
		<tr>
			<?php if (!empty($pager)) {?><td style="text-align:right;" colspan="100" ><?=$pager?></td><?php }?>
		</tr>
	</tbody>
</table>