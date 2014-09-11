<?php $conf = Load::conf('PurchaseOrder');?>
<table class="table table-hover">
	<thead>
		<tr>
			<th><?=LG_PO_CODE?></th>
			<th><?=LG_PO_SUPPLIER?></th>
			<th><?=LG_PO_WAREHOUSE?></th>
			<th><?=LG_PO_DEMAND_TIME?></th>
			<th><?=LG_PO_TYPE?></th>
			<th><?=LG_PO_CREATE_TIME?></th>
			<th><?=LG_PO_COMMIT_TIME?></th>
			<th><?=LG_PO_CREATE_USER?></th>
			<th><?=LG_PO_SUM?></th>
			<th><?=LG_PO_AMOUNT?></th>
			<th><?=LG_PO_CURRENCY?></th>
			<th><?=LG_PO_WARRANTY?></th>
			<th><?=LG_PO_STATUS?></th>
			<th><?=LG_PO_REMARK?></th>
			<th><?=LG_OPERATION?></th>
		</tr>
	</thead>
	<tbody>
		<?php
		if (!empty($list)) { foreach ($list as $v) {
		?>
		<tr>
			<td><a href="<?=url('purchaseOrder/view/'.$v['id'])?>" target="_blank"><?=$v['code']?></a></td>
			<td><?=$v['supplier']?></td>
			<td><?=$v['warehouse']?></td>
			<td><?=$v['demandTime']?></td>
			<td><?=$conf['type'][$v['type']]?></td>
			<td><?=$v['createTime']?></td>
			<td><?=$v['commitTime']?></td>
			<td><?=$v['nickname']?></td>
			<td><?=$v['pnSum']?></td>
			<td><?=$v['amount']?></td>
			<td><?=$v['currency']?></td>
			<td><?=$conf['warranty'][$v['warranty']]?></td>
			<td><?=$conf['status'][$v['status']]?></td>
			<td><?=$v['remark']?></td>
			<td>
				<div class="btn-group">
				  	<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
				  	<ul class="dropdown-menu pull-right">
						<?php if (PurchaseOrder::canChange($v)) {?>
						<li><a href="<?=url('purchaseOrder/change/'.$v['id'])?>"><i class="icon-edit"></i> <?=LG_BTN_EDIT?></a></li>
						<?php }?>
						<?php if (PurchaseOrder::canDel($v)) {?>
						<li><a name="del" title="<?=LG_PO_DEL?>" href="<?=url('purchaseOrder/del/'.$v['id'])?>"><i class="icon-trash"></i> <?=LG_BTN_DEL?></a></li>
						<?php }?>
						<?php if (PurchaseOrder::canPN($v)) {?>
						<li><a href="<?=url('purchaseOrder/pn/'.$v['id'])?>"><i class="icon-wrench"></i> PN</a></li>
						<?php }?>
						<?php if (PurchaseOrder::canApprove($v)) {?>
						<li><a href="<?=url('purchaseOrder/approve/'.$v['id'])?>"><i class="icon-check"></i> <?=LG_PO_APPROVE?></a></li>
						<?php }?>
						<?php if (PurchaseOrder::canClose($v)) {?>
						<li><a name="del" title="<?=LG_PO_CLOSE?>" body="<?=LG_CLOSE_CONFIRM?>" href="<?=url('purchaseOrder/close/'.$v['id'])?>"><i class="icon-power-off"></i> <?=LG_BTN_CLOSE?></a></li>
						<?php }?>
					</ul>
				</div>
			</td>
		</tr>
		<?php }}?>
		<?php if (!empty($pager)) { ?><tr><td colspan="20" style="text-align:right;"><?=$pager?></td><?php }?>
	</tbody>
</table>