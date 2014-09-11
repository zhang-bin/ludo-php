<?php $conf = Load::conf('PurchaseOrder');?>
<table class="table table-hover">
	<thead>
		<tr>
			<th><?=LG_PO_CODE?></th>
			<th><?=LG_PO_SUPPLIER?></th>
			<th><?=LG_PO_WAREHOUSE?></th>
			<th><?=LG_PN?></th>
			<th><?=LG_PO_PRICE?></th>
			<th><?=LG_PO_QTY?></th>
			<th><?=LG_PO_PN_AMOUNT?></th>
			<th><?=LG_PO_AOG?></th>
			<th><?=LG_PO_NON_ARRIVAL?></th>
			<th><?=LG_PO_STATUS?></th>
			<th><?=LG_PO_CLOSE_REASON?></th>
			<th><?=LG_PO_PN_REMARK?></th>
		</tr>
	</thead>
	<tbody>
		<?php
		if (!empty($list)) { foreach ($list as $v) {$unitPrice = Crypter::decrypt($v['unitPrice']);
		?>
		<tr>
			<td><a href="<?=url('purchaseOrder/view/'.$v['purchaseOrderId'])?>" target="_blank"><?=$v['code']?></a></td>
			<td><?=$v['supplier']?></td>
			<td><?=$v['warehouse']?></td>
			<td><?=$v['pn']?></td>
			<td><?=$unitPrice?></td>
			<td><?=$v['qty']?></td>
			<td><?=$v['qty'] * $unitPrice?></td>
			<td><?=$v['aog']?></td>
			<td><?=$v['qty'] - $v['aog']?></td>
			<td><?=$conf['pnStatus'][$v['status']]?></td>
			<td><?=$conf['closeReason'][$v['closeReason']]?></td>
			<td><?=$v['remark']?></td>
		</tr>
		<?php }}?>
		<?php if (!empty($pager)) { ?><tr><td colspan="20" style="text-align:right;"><?=$pager?></td><?php }?>
	</tbody>
</table>