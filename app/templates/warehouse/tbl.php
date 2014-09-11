<table class="table table-hover">
	<thead>
	<tr>
		<th>Warehouse Name</th>
		<th>Address</th>
		<th>Good/Defect</th>
		<th>Service Vendor</th>
		<th>Station</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach($list as $k=>$v) {?>
	<tr>
		<td><?=$v['name']?></td>
		<td><?=$v['address'] ?></td>
		<td><?=Warehouse::$_types[$v['goodOrBad']]?></td>
		<td><?=$v['vendorName']?></td>
		<td><?=$v['stationName']?></td>
	</tr>
	<?php }?>
	<?php if(!empty($pager)){?>
	<tr>
		<td style="text-align: right;" colspan="8"><?=$pager?></td>
	</tr>
	<?php }?>
	</tbody>
</table>