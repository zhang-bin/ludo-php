<?php
$gTitle = LG_PO_INFO;
include tpl('header');
$conf = Load::conf('PurchaseOrder');
?>
<style>
.controls{line-height:30px;}
.control-group{margin-bottom:0px !important;}
</style>
<form id="form1" class="form-horizontal">
	<div class="control-group">
		<label class="control-label"><strong><?=LG_PO_CODE?></strong>:</label>
		<div class="controls"><?=$order['code']?></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_PO_SUPPLIER?></strong>:</label>
		<div class="controls"><?=$order['supplier']?></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_PO_WAREHOUSE?></strong>:</label>
		<div class="controls"><?=$order['warehouse']?></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_PO_TYPE?></strong>:</label>
		<div class="controls"><?=$conf['type'][$order['type']]?></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_PO_STATUS?></strong>:</label>
		<div class="controls"><?=$conf['status'][$order['status']]?></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_PO_APPROVE?></strong>:</label>
		<div class="controls"><?=$conf['approve'][$order['approve']]?></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_PO_CREATE_TIME?></strong>:</label>
		<div class="controls"><?=$order['createTime']?></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_PO_COMMIT_TIME?></strong>:</label>
		<div class="controls"><?=$order['commitTime']?></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_PO_DEMAND_TIME?></strong>:</label>
		<div class="controls"><?=$order['demandTime']?></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_PO_CURRENCY?></strong>:</label>
		<div class="controls"><?=$order['currency']?></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_PO_WARRANTY?></strong>:</label>
		<div class="controls"><?=$conf['warranty'][$order['warranty']]?></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_PO_CREATE_USER?></strong>:</label>
		<div class="controls"><?=$order['nickname']?></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_PO_AMOUNT?></strong>:</label>
		<div class="controls"><?=$order['amount']?></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_PO_SUM?></strong>:</label>
		<div class="controls"><?=$order['pnSum']?></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_PO_REMARK?></strong>:</label>
		<div class="controls"><?=$order['remark']?></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_PO_PN?></strong>:</label>
		<div class="controls" id="approveTbl"></div>
	</div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_PO_APPROVE_REMARK?></strong>:</label>
		<div class="controls"><textarea name="approveRemark" id="approveRemark" rows="3" class="span4"></textarea></div>
	</div>
	<div class="control-group" style="margin-top:10px;">
		<div class="controls">
			<input type="hidden" id="id" name="id" value="<?=$order['id']?>"/>
			<input id="agree" type="button" value="<?=LG_BTN_AGREE?>" class="btn btn-success" />
			<input id="reject" type="button" value="<?=LG_BTN_REJECT?>" class="btn btn-danger" />
			<input type="button" onclick="javascript:history.back();" value="<?=LG_BTN_CANCEL?>" class="btn" />
		</div>
	</div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	$("#approveTbl").loading("<?=url('purchaseOrder/approveTbl/'.$order['id'])?>");
	$("#approveTbl").on("click", "a.p", function(){
		$("#approveTbl").loading(this.href);
		return false;	
	});
	$('#agree').click(function(){
		$("#agree").attr("disabled", "disabled");
        $.post("<?= url('purchaseOrder/agree')?>", $("#form1").serialize(), function(result) {
        	$("#agree").removeAttr("disabled");
            if(ajaxHandler(result)) return;
        });
        return false;
	});
	$('#reject').click(function(){
		$("#reject").attr("disabled", "disabled");
        $.post("<?= url('purchaseOrder/reject')?>", $("#form1").serialize(), function(result) {
        	$("#reject").removeAttr("disabled");
            if(ajaxHandler(result)) return;
        });
        return false;
	});
	
});
</script>
<?php include tpl('footer');?>