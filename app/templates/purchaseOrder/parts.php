<?php
$gTitle = LG_PO_QUERY;
include tpl('header');
$conf = Load::conf('PurchaseOrder');
?>
<form id="form1" class="form-inline">
	<?=LG_PO_STATUS?>:
	<select name="status[]" multiple="multiple" size="5" class="selectpicker" data-selected-text-format="count">
		<?php foreach ($conf['pnStatus'] as $k=>$status) {?>
		<option value="<?=$k?>"><?=$status?></option>
		<?php }?>
	</select>
	&emsp;
	<?=LG_PO_CODE?>:
	<input type="text" name="po" value=""></input>
	&emsp;
	<?=LG_PN?>:
	<input type="text" name="pn" value=""></input>
	&emsp;
	<?=LG_PO_SUPPLIER?>:
	<select name="suppliers[]" multiple="multiple" size="5" class="selectpicker" data-selected-text-format="count">
		<?php foreach ($suppliers as $supplier) {?>
		<option value="<?=$supplier['id']?>"><?=$supplier['supplier']?></option>
		<?php }?>
	</select>
	<input type="submit" id="search" class="btn btn-primary btn-small" value="<?=LG_BTN_SEARCH?>"></input>
	<a class="excel" href="<?=url('purchaseOrder/partsReport')?>" id="excel" style="float:right;margin-right:5px;"></a>
</form>
<div id="partsList"></div>
<script type="text/javascript">
$(document).ready(function(){
	$("#partsList").loading("<?=url('purchaseOrder/partsTbl')?>", $("#form1").serializeArray());
	$("#form1").submit(function(){
		$("#partsList").loading("<?=url('purchaseOrder/partsTbl')?>", $(this).serializeArray());
		return false;
	});
	$("a.p").live("click", function(){
		$("#partsList").loading(this.href, $("#form1").serializeArray());
		return false;
	});
	$("#excel").click(function(){
		$.posting(this.href, $("#form1").serializeArray(), function(result){
			ajaxHandler(result);
			return false;
		});
		return false;
	});
});
</script>
<?php include tpl('footer');?>