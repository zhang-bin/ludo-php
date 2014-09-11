<?php
$gTitle = LG_PO_PN;
include tpl('header');
$conf = Load::conf('PurchaseOrder');
Load::js('autocomplete');
?>
<div class="form-horizontal">
	<div class="control-group">
		<label class="control-label"><strong><?=LG_PO_CODE?></strong>:</label>
		<div class="controls"><p class="form-control-static"><?=$order['code']?></p></div>
    </div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_PO_SUPPLIER?></strong>:</label>
		<div class="controls"><p class="form-control-static"><?=$order['supplier']?></p></div>
    </div>
	<div class="control-group">
		<label class="control-label"><strong><?=LG_PO_WAREHOUSE?></strong>:</label>
		<div class="controls"><p class="form-control-static"><?=$order['warehouse']?></p></div>
	</div>
</div>
<div id="pnTbl"></div>
<?php if ($order['status'] == PurchaseOrder::STATUS_PROCESS || $order['status'] == PurchaseOrder::STATUS_BACK) {?>
<p style="float: right;">
	<input id="submitBtn" type="button" value="<?=LG_BTN_SAVE?>" class="btn btn-primary" />
	<input type="button" value="<?=LG_BTN_CANCEL?>" class="btn" onclick="javascript:history.back();" />
</p>
<?php }?>
<div class="pn-modal modal hide fade">
 	<div class="modal-header">
   		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
   		<h4 class="modal-title"><?=LG_PO_PN_CHANGE?></h4>
   	</div>
	<div class="modal-body" id="pn-body"></div>
	<div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?=LG_BTN_CANCEL?></button>
        <button type="button" class="btn btn-primary" id="confirmPN"><?=LG_BTN_CONFIRM?></button>
	</div>
</div>
<div class="cancel-modal modal hide fade">
 	<div class="modal-header">
   		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
   		<h4 class="modal-title"><?=LG_PO_PN_CANCEL?></h4>
   	</div>
	<div class="modal-body" id="pn-body"></div>
	<div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?=LG_BTN_CANCEL?></button>
        <button type="button" class="btn btn-primary" id="confirmCancel"><?=LG_BTN_CONFIRM?></button>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$("#pnTbl").loading("<?=url('purchaseOrder/pnTbl/'.$order['id'])?>");
	$("#pnTbl").on("click", "#add", function(){
		$.posting("<?=url('purchaseOrder/addPn')?>", $(".add").serialize(), function(result){
			var result = result.split('|');
			var title = result[0].trim();
			var info = result[1];
			switch (title) {
				case 'success':
					$("#pnTbl").loading("<?=url('purchaseOrder/pnTbl/'.$order['id'])?>");
					return true;
				case 'alert':
					alert(info);
					return false;
				default:
					return false;
			}
		});
		return false;
	});
	$("#pnTbl").on("focus", ".pn", function(){
		var i = $(this);
		i.autocomplete({
			serviceUrl: "<?=url('purchaseOrder/suggestParts?supplierId='.$order['supplierId'].'&currency='.$order['currency'])?>",
			onSelect: function(suggest){
				$(this).val(suggest.data);
                var lt = suggest.lt;
                if (lt == '0') lt = 'N/A';
				i.closest("tr").find("#addPnLt").text(lt);
                i.closest("tr").find("#addPnDescr").text(suggest.en);
                i.closest("tr").find("#addPnPrice").text(suggest.price);
			}
		});
	});
	$(".pn-modal").on("focus", ".pn", function(){
		var i = $(this);
		i.autocomplete({
			serviceUrl: "<?=url('purchaseOrder/suggestParts?supplierId='.$order['supplierId'].'&currency='.$order['currency'])?>",
			onSelect: function(suggest){
				$(this).val(suggest.data);
				$("#pn_change").find(".descr").text(suggest.en);
				$("#pn_change").find(".price").text(suggest.price);
			}
		});
	});

	$(".pn-modal").on("keyup", ".qty", function(){
		var price = $("#pn_change").find(".price").text();
		var qty = $(this).val();
		var amount = Math.round(price * qty * 100) / 100;
		$("#pn_change").find(".amount").text(amount);
	});

	$("#confirmPN").click(function(){
		$.posting("<?=url('purchaseOrder/changePN')?>", $("#pn_change").serializeArray(), function(result) {
			ajaxHandler(result);
			return false;
		})
		return false;
	});
	$("#confirmCancel").click(function(){
		$.posting("<?=url('purchaseOrder/cancelPN')?>", $("#pn_cancel").serializeArray(), function(result) {
			ajaxHandler(result);
			return false;
		})
		return false;
	});
	
	$("#pnTbl").on("click", "a.p", function(){
		$("#pnTbl").loading(this.href);
		return false;
	});
	$("#pnTbl").on("click", "[name=edit]", function(){
		$(".pn-modal").find("#pn-body").loading(this.href);
		$(".pn-modal").modal();
		return false;
	});
	
	$("#pnTbl").on("click", "[name=cancel]", function(){
		$(".cancel-modal").find("#pn-body").loading(this.href);
		$(".cancel-modal").modal();
		return false;
	});

	$("#pnTbl").on("keyup", "[name=aog]", function(){
		var aog = $(this).val();
		var td = $(this).parent().prev().prev();
		if (td.find("input").length > 0) {
			var qty = td.find("input").val();
		} else {
			var qty = td.text();
		}
			
		$(this).parent().next().text(qty - aog);
	});
	$("#pnTbl").on("keyup", "[name=qty]", function(){
		var qty = $(this).val();
		var td = $(this).parent().prev();
		if (td.find("input").length > 0) {
			var price = td.find("input").val();
		} else {
			var price = td.text();
		}
			
		$(this).parent().next().text(Math.round(qty * price * 100) / 100);
	});

	$('#submitBtn').click(function(){
		$(this).attr("disabled", "disabled");
		$.posting("<?=url('purchaseOrder/submit')?>", {"id": "<?=$order['id']?>"}, function(result) {
			$("#submitBtn").removeAttr("disabled");
			if (ajaxHandler(result)) return false;
			return false;
		}); 
		return false;
	});
});
</script>
<?php include tpl('footer');?>