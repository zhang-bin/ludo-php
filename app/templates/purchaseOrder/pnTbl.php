<style>
.pn{width:100px;}
.qty,.aog{width:90px;}
select{width:100px;}
</style>
<?php $conf = Load::conf('PurchaseOrder');?>
<table class="table table-hover">
	<thead>
		<tr>
			<th><?=LG_PN?></th>
			<th><?=LG_PN_LT?></th>
			<th><?=LG_PO_DESCR?></th>
			<th><?=LG_PO_PRICE?></th>
			<th><?=LG_PO_QTY?></th>
			<th><?=LG_PO_PN_AMOUNT?></th>
			<th><?=LG_PO_AOG?></th>
			<th><?=LG_PO_NON_ARRIVAL?></th>
			<th><?=LG_PO_STATUS?></th>
			<th><?=LG_PO_CLOSE_REASON?></th>
			<th><?=LG_PO_PN_REMARK?></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
	<?php if (!empty($details)) {foreach ($details as $detail) {$price = Crypter::decrypt($detail['unitPrice']);?>
	<tr>
		<td><?=$detail['pn']?></td>
		<td><?=($detail['leadTime'] == '0') ? 'N/A' : $detail['leadTime']?></td>
		<td><?=$detail['en']?></td>
		<td><?=$price?></td>
		<td><?=$detail['qty']?></td>
		<td><?=round($detail['qty'] * $price, 2)?></td>
		<td><?=$detail['aog']?></td>
		<td><?=$detail['qty']-$detail['aog']?></td>
		<td><?=$conf['pnStatus'][$detail['status']]?></td>
		<td><?=$conf['closeReason'][$detail['closeReason']]?></td>
		<td><?=$detail['remark']?></td>
		<td>
			<?php if ($detail['status'] != PurchaseOrder::PN_STATUS_CANCEL) {?>
			<div class="btn-group">
			  	<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
			  	<ul class="dropdown-menu pull-right">
				<li><a name="edit" href="<?=url('purchaseOrder/changePn/'.$detail['id'])?>"><i class="icon-edit"></i> <?=LG_BTN_EDIT?></a></li>
				<?php if ($order['status'] == PurchaseOrder::STATUS_PROCESS) {?>
				<li><a name="del" title="<?=LG_PO_PN_DEL?>" href="<?=url('purchaseOrder/delPn/'.$detail['id'])?>"><i class="icon-trash"></i> <?=LG_BTN_DEL?></a></li>
				<?php }?>
				<li><a name="cancel" href="<?=url('purchaseOrder/cancelPn/'.$detail['id'])?>"><i class="icon-remove"></i> <?=LG_BTN_CANCEL?></a></li>
			  	</ul>
			</div>
			<?php }?>
		</td>
	<?php }}?>
	<tr>
		<td><input type="text" class="add addPn pn" name="pn" /></td>
		<td id="addPnLt"></td>
		<td id="addPnDescr"></td>
		<td id="addPnPrice"></td>
		<td><input type="text" class="add qty" name="qty" /></td>
		<td></td>
		<td><input type="text" class="add aog" name="aog" /></td>
		<td></td>
		<td><select name="status">
			<?php foreach ($conf['pnStatus'] as $k => $v) {?>
			<option value="<?=$k?>"><?=$v?></option>
			<?php }?>
		</select></td>
		<td></td>
		<td><input type="text" class="add remark" name="remark" style="width:90%;" /></td>
		<td>
			<input type="hidden" class="add" name="id" value="<?=$id?>" />
			<input type="button" class="btn btn-success btn-block"  id="add" value="<?=LG_BTN_ADD?>" />
		</td>
	</tr>
	<?php if (!empty($pager)) { ?><tr><td colspan="20" style="text-align:right;"><?=$pager?></td><?php }?>
	</tbody>
</table>