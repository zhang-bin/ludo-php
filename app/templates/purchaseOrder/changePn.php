<style>
.control-group{margin-bottom:10px !important;}
</style>
<?php 
$conf = Load::conf('PurchaseOrder');
$price = Crypter::decrypt($detail['unitPrice']);
?>
<form id="pn_change" class="form-horizontal">
	<div class="control-group">
		<label class="control-label"><strong><?=LG_PN?></strong></label>
		<div class="controls"><input type="text" class="pn span2" name="pn" value="<?=$detail['pn']?>" /></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_PN_LT?></strong></label>
		<div class="controls leadTime"><p class="form-control-static"><?=($detail['leadTime'] == '0') ? 'N/A' : $detail['leadTime']?></p></div>
	</div>
    <div class="control-group">
		<label class="control-label"><strong><?=LG_PO_DESCR?></strong></label>
		<div class="controls descr"><p class="form-control-static"><?=$detail['en']?></p></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_PO_DESCR?></strong></label>
		<div class="controls price"><p class="form-control-static"><?=$price?></p></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_PO_QTY?></strong></label>
		<div class="controls"><input type="text" class="qty span2" name="qty" value="<?=$detail['qty']?>" /></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_PO_PN_AMOUNT?></strong></label>
		<div class="controls amount"><p class="form-control-static"><?=round($price * $detail['qty'], 2)?></p></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_PO_AOG?></strong></label>
		<div class="controls"><input type="text" class="aog span2" name="aog" value="<?=$detail['aog']?>" /></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_PO_STATUS?></strong></label>
		<div class="controls"><select name="status">
			<?php foreach ($conf['pnStatus'] as $k => $v) {?>
			<option value="<?=$k?>" <?=($k == $detail['status']) ? 'selected' : ''?>><?=$v?></option>
			<?php }?>
		</select></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_PO_CLOSE_REASON?></strong></label>
		<div class="controls"><select name="closeReason">
			<option value="0"><?=LG_SELECT_CHOOSE?></option>
			<?php foreach ($conf['closeReason'] as $k => $v) {?>
			<option value="<?=$k?>" <?=($k == $detail['closeReason']) ? 'selected' : ''?>><?=$v?></option>
			<?php }?>
		</select></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_PO_PN_REMARK?></strong></label>
		<div class="controls"><input type="text" class="add remark span3" name="remark" style="width:90%;" value="<?=$detail['remark']?>" /></div>
	</div>
	<input type="hidden" name="id" value="<?=$detail['id']?>" />
</form>