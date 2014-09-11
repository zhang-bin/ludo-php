<?php 
$conf = Load::conf('PurchaseOrder');
$price = Crypter::decrypt($detail['unitPrice']);
?>
<form id="pn_cancel" class="form-horizontal">
	<div class="control-group">
		<label class="control-label"><strong><?=LG_PN?></strong></label>
		<div class="controls"><p class="form-control-static"><?=$detail['pn']?></p></div>
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
		<label class="control-label"><strong><?=LG_PO_PRICE?></strong></label>
		<div class="controls price"><p class="form-control-static"><?=$price?></p></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_PO_QTY?></strong></label>
		<div class="controls"><p class="form-control-static"><?=$detail['qty']?></p></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_PO_PN_AMOUNT?></strong></label>
		<div class="controls amount"><p class="form-control-static"><?=round($price * $detail['qty'], 2)?></p></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_PO_PN_REMARK?></strong></label>
		<div class="controls"><input type="text" class="add remark span3" name="remark" value="" /></div>
	</div>
	<input type="hidden" name="id" value="<?=$detail['id']?>" />
</form>