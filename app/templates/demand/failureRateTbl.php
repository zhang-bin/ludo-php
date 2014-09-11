<table class="table table-hover" width="100%">
	<thead>
		<tr>
			<th>Parts Category</th>
			<th>Model</th>
			<th>Country</th>
			<th>Setting Failure Rate</th>
			<th>Current Sales Volume</th>
			<th>FCST Monthly Demand</th>
		</tr>
	</thead>	
	<tbody>
		<?php if (!empty($mffrs)) { foreach ($mffrs as $mffr) {?>
		<tr>
			<td><?=$mffr['category']?></td>
			<td><?=$mffr['model']?></td>
			<td><?=$mffr['country']?></td>
			<td><?=$mffr['rate']?>%</td>
			<td><?=$mffr['warranty']?></td>
			<td><?=$mffr['demand']?></td>
		</tr>
		<?php }}?>
		<?php if (!empty($pager)) { ?><tr><td colspan="12" style="text-align:right;"><?=$pager?></td><?php }?>
	</tbody>
</table>