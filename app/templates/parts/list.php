<table class="table table-hover">
	<thead>
	<tr>
		<th>New PN</th>
		<th>Old PN</th>
		<th>Parts Name</th>
		<th>Parts Category</th>
        <th>Model</th>
        <th>Parts Group</th>
		<th>Repair Level</th>
		<th>NPI flag</th>
		<th>Active</th>
		<th>Purchasable</th>
		<th>EOL flag</th>
		<th>Slow Moving</th>
		<th>Obsolete</th>
	</tr>
	</thead>
	<tbody>		
	<?php
	if (!empty($list)) { foreach ($list as $v) {
	?>
	<tr>
		<td><?=$v['pn']?></td>	
		<td><?=$v['pn3']?></td>
		<td><?=$v['en']?></td>
		<td><?=$v['partsGroupName']?></td>
		<td style="word-break: break-all; word-wrap: break-word;"><?=$v['models']?></td>
		<td><?=$v['groupNo']?></td>
		<td><?=$v['level']?></td>
        <td><input type="checkbox" disabled <?=$v['npiLog'] ? 'checked' : ''?> /></td>
		<td><input type="checkbox" disabled <?=$v['active'] ? 'checked' : ''?> /></td>
		<td><input type="checkbox" disabled <?=$v['purchasable'] ? 'checked' : ''?> /></td>
		<td><input type="checkbox" disabled <?=$v['EOL'] ? 'checked' : ''?> /></td>
		<td><input type="checkbox" disabled <?=$v['slowMoving'] ? 'checked' : ''?> /></td>
		<td><input type="checkbox" disabled <?=$v['obsolete'] ? 'checked' : ''?> /></td>
	</tr>
	<?php 
	}}
	?>
	<?php if (!empty($pager)) { ?><tr><td colspan="13" style="text-align:right;"><?=$pager?></td><?php }?>
	</tbody>
</table>