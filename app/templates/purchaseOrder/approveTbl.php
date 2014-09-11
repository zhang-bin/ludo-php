<table class="table table-hover">
	<thead>
		<tr>			
			<th><?=LG_PN?></th>
			<th><?=LG_PO_PRICE?></th>		
			<th><?=LG_PO_QTY?></th>
			<th><?=LG_PO_PN_AMOUNT?></th>
			<th><?=LG_PO_AOG?></th>
			<th><?=LG_PO_PN_REMARK?></th>
		</tr>
	</thead>
	<tbody>
		<?php if (!empty($details)) {foreach ($details as $detail) {$price = Crypter::decrypt($detail['unitPrice']);?>
		<tr>
			<td><?=$detail['pn']?></td>
			<td><?=$price?></td>
			<td><?=$detail['qty']?></td>
			<td><?=round($detail['qty'] * $price, 4)?></td>
			<td><?=$detail['aog']?></td>
			<td><?=$detail['remark']?></td>
		<?php }}?>
		<?php if (!empty($pager)) { ?><tr><td colspan="20" style="text-align:right;"><?=$pager?></td><?php }?>
	</tbody>
</table>