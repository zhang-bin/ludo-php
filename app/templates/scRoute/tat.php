<?php
$conf = Load::conf('Setting');
?>
<table class="table table-hover table-bordered table-condensed">
	<thead>
		<tr>	
			<th></th>
			<th><?=LG_SC_ROUTE_TAT_FROM?></th>
			<th><?=LG_SC_ROUTE_TAT_TO?></th>
			<th><?=LG_SC_ROUTE_TAT_WAY?></th>
			<th><?=LG_SC_ROUTE_TAT_DAY?></th>
			<th><?=LG_SC_ROUTE_TAT_FEE?></th>
		</tr>	
	</thead>
	<tbody>	
		<?php if (!empty($tats)) { foreach ($tats as $tat) {?>
		<tr>
			<td><input type="checkbox" name="tat[]" value="<?=$tat['id']?>" <?=($tat['checked']) ? 'checked' : ''?> /></td>
			<td><?=$tat['fromPoint']?></td>
			<td><?=$tat['toPoint']?></td>
			<td><?=$conf['transport'][$tat['transportWay']]?></td>
			<td><?=$tat['consumeDays']?></td>
			<td><?=$tat['fee']?></td>
		</tr>
		<?php }}?>
	</tbody>
</table>