<table class="table table-hover">
	<thead>
		<tr>
			<th>PN</th>
			<th>Parts Name</th>
			<th>Parts Category</th>
			<th>Destination Depot</th>
			<th>Good Parts Qty</th>
		</tr>
	</thead>
	<tbody>
		<?php if (!empty($shippings)) {foreach ($shippings as $shipping) {?>
		<tr>
			<td><?=$shipping['pn']?></td>
			<td><?=$shipping['en']?></td>
			<td><?=$shipping['partsGroupName']?></td>
			<td><?=$shipping['countryShortName']?></td>
			<td><?=$shipping['qty']?></td>
		</tr>
		<?php }}?>
		<?php if (!empty($pager)) { ?><tr><td colspan="30" style="text-align:right;"><?=$pager?></td><?php }?>
	</tbody>
</table>