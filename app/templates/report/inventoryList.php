<table class="table table-hover">
	<thead>
		<tr>
			<th>PN</th>
			<th>Parts Name</th>
			<th>Parts Category</th>
			<th>Service Vendor</th>
			<th>Warehouse</th>
			<th>Good/Defect</th>
			<th>Month</th>
			<th>Qty</th>
		</tr>
	</thead>
	<tbody>
		<?php if (!empty($inventories)) {foreach ($inventories as $inventory) {?>
		<tr>
			<td><?=$inventory['pn']?></td>
			<td><?=$inventory['en']?></td>
			<td><?=$inventory['partsGroupName']?></td>
			<td><?=$inventory['countryShortName']?></td>
			<td><?=$inventory['name']?></td>
			<td><?=Warehouse::$_types[$inventory['goodOrBad']]?></td>
			<td><?=$inventory['month']?></td>
			<td><?=$inventory['qty']?></td>
		</tr>
		<?php }}?>
		<?php if (!empty($pager)) { ?><tr><td colspan="30" style="text-align:right;"><?=$pager?></td><?php }?>
	</tbody>
</table>