<table class="table table-bordered">
	<thead>
		<tr>
			<th><input type="checkbox" class="checkAll" /></th>
			<th><?=LG_PN?></th>
			<th><?=LG_PN_QTY?></th>
			<th><?=LG_PN_AOG?></th>
			<th><?=LG_PN_HAS_DELIVERY?></th>
		</tr>
	</thead>
	<tbody>
		<?php if (!empty($details)) {foreach ($details as $detail) {?>
		<tr>
			<td><input type="checkbox" class="checkOne" name="detail[<?=$detail['id']?>]" uid="<?=$detail['id']?>" /></td>
			<td class="pn"><?=$detail['pn']?></td>
			<td class="qty"><?=$detail['qty']?></td>
			<td class="aog"><?=$detail['aog']?></td>
			<td class="delivery"><?=$detail['delivery']?></td>
		<?php }}?>
		<?php if (!empty($pager)) { ?><tr><td colspan="20" style="text-align:right;"><?=$pager?></td><?php }?>
	</tbody>
</table>