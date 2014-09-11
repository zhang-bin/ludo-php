<table class="table table-hover">
	<thead>
		<tr>
			<th>PN</th>
			<th>Parts Name</th>
			<th>Parts Category</th>
			<th>Service Vendor</th>
			<th>Month</th>
			<th>Qty</th>
		</tr>
	</thead>
	<tbody>
		<?php if (!empty($usages)) {foreach ($usages as $usage) {?>
		<tr>
			<td><?=$usage['pn']?></td>
			<td><?=$usage['en']?></td>
			<td><?=$usage['partsGroupName']?></td>
			<td><?=$usage['countryShortName']?></td>
			<td><?=$usage['month']?></td>
			<td><?=$usage['qty']?></td>
		</tr>
		<?php }}?>
		<?php if (!empty($pager)) { ?><tr><td colspan="30" style="text-align:right;"><?=$pager?></td><?php }?>
	</tbody>
</table>