<?php 
$gTitle = LG_SETTING_PARTS_LT;
$gToolbox = '<a href="'.url('basicData/importPnLt').'" class="add">Import L/T</a>
		<a href="'.url('basicData/addPnLt').'" class="add">Add L/T</a>';
include tpl('header');
?>
<form id="searchPnLt" class="form-inline">
	<input type="text" name="pn" placeholder="<?=LG_PURCHASE_PN?>" />
	<input type="submit" class="btn btn-primary btn-small" value="<?=LG_BTN_SEARCH?>" />
</form>
<div id="pnLt" style="margin-top:10px;"></div>
<script type="text/javascript">
$(document).ready(function(){
	$("#pnLt").loading("<?=url('basicData/pnLtTbl')?>");
	$("#pnLt").on("click", "a.p", function(){
		$("#pnLt").loading(this.href, $("#searchPnLt").serializeArray());
		return false;
	});
	$("#searchPnLt").submit(function(){
		$("#pnLt").loading("<?=url('basicData/pnLtTbl')?>", $("#searchPnLt").serializeArray());
		return false;
	});
});
</script>
<?php include tpl('footer');?>