<?php 
$gTitle = LG_PARTSPRICE_HISTORY;
include tpl('header');
$conf = Load::conf('partsPrice');
?>
<table class="table table-hover">
	<thead>
		<tr>
			<th>Part Number</th>
			<th>Supplier</th>
			<th>Price Type</th>
			<th>USD Price</th>
			<th>RMB Price</th>
			<th>Begin Time</th>
			<th>End Time</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($prices as $price) {?>
		<tr>
			<td><?=$price['pn']?></td>
			<td><?=$price['supplier']?></td>
			<td><?=$conf['type'][$price['priceType']]?></td>
			<td><?=sprintf('%.2f', Crypter::decrypt($price['usd']))?></td>
			<td><?=sprintf('%.2f', Crypter::decrypt($price['rmb']))?></td>
			<td><?=$price['beginTime']?></td>
			<td><?=$price['endTime']?></td>
		</tr>
		<?php }?>
		<?php if(!empty($pager)){?>
		<tr>
			<td style="text-align: right;" colspan="10"><?=$pager?></td>
		</tr>
		<?php }?>
	</tbody>
</table>