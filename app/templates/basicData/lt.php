<form id="searchLt" class="form-inline">
	<?=LG_LT_SUPPLIER?>:
	<select name="supplier">
	  	<option value="0"><?=LG_SELECT_CHOOSE?></option>
	 	<?php foreach ($suppliers as $supplier) {?>
	 	<option value="<?=$supplier['id']?>"><?=$supplier['supplier']?></option>
	  	<?php }?>
	</select>
	<input type="submit" class="btn btn-primary btn-small" value="<?=LG_BTN_SEARCH?>" />
	<a id="addLt" class="btn btn-success jump" style="float: right;" href="<?=url('basicData/addLt')?>"><?=LG_LT_ADD?></a>
</form>
<div id="lt" style="margin-top:10px;"></div>
<script type="text/javascript">
$(document).ready(function(){
	$("#lt").loading("<?=url('basicData/ltTbl')?>");
	$("#lt").on("click", "a.p", function(){
		$("#lt").loading(this.href, $("#searchLt").serializeArray());
		return false;
	});
	$("#searchLt").submit(function(){
		$("#lt").loading("<?=url('basicData/ltTbl')?>", $("#searchLt").serializeArray());
		return false;
	});
});
</script>