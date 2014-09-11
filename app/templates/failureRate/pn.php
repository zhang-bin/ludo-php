<table class="table table-hover">
	<thead>
		<tr>
			<th><?=LG_FAILURE_RATE_PN?></th>
			<th><?=LG_FAILURE_RATE_PN_EN?></th>
			<th><?=LG_FAILURE_RATE_QTY?></th>
			<th><?=LG_FAILURE_RATE_WARRANTY?></th>
			<th><?=LG_FAILURE_RATE_RATE?></th>
		</tr>
	</thead>
	<tbody>
		<?php if (!empty($pns)) {foreach ($pns as $pn => $qty) {?>
		<tr>
			<td><?=$pn?></td>
			<td><?=$qty['en']?></td>
			<td><?=$qty['qty']?></td>
			<td><?=$qty['warranty']?></td>
			<td><?=$qty['rate']?></td>
		</tr>	
		<?php }}?>	
		<tr>
			<?php if (!empty($pager)) {?><td style="text-align:right;" colspan="20" ><?=$pager?></td><?php }?>
		</tr>
	</tbody>
</table>