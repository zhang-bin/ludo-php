<table class="table table-hover">
	<thead>
		<tr>
            <th>Country</th>
			<th>Model Name</th>
			<th>New Model PN</th>
			<th>BOM Level</th>
			<th>Maintenance Level</th>
			<th>Parts PN</th>
			<th>Quantity</th>
			<th>Unit</th>
			<th>Position Number</th>
			<th>Replaceable Parts</th>
		</tr>	
	</thead>
	<tbody>	
		<?php if (!empty($list)) { foreach ($list as $v) {?>
		<tr>
			<td><?=$v['country']?></td>
			<td><?=$v['modelName']?></td>
			<td><?=$v['modelPN3']?></td>
			<td><?=$v['level']?></td>
			<td><?=$v['maintainlevel']?></td>
			<td><?=$v['pn']?></td>
			<td><?=$v['bomqty']?></td>
			<td><?=$v['unit']?></td>
			<td><?=$v['positionNumber']?></td>
			<td><?=$v['replaceNumber']?></td>
		</tr>
		<?php }}?>
		<?php if (!empty($pager)) { ?><tr><td colspan="11" style="text-align:right;"><?=$pager?></td><?php }?>
	</tbody>
</table>