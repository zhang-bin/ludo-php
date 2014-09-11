<?php
$gTitle = LG_TO_WARNING;
include tpl('header');
?>
<form id="form1" class="form-inline">
	<select name="vendor" class="selectpicker" data-live-search="true">
		<option value="0">All Service Vendor</option>
		<?php foreach ($vendors as $vendor) {if (empty($vendor['countryShortName'])) continue;?>
		<option value="<?=$vendor['id']?>"><?=$vendor['countryShortName']?></option>
		<?php }?>
	</select>
	<input type="text" name="pn" placeholder="<?=LG_WARNING_PN?>" />
	<input type="submit" value="<?=LG_BTN_SEARCH?>" class="btn btn-small btn-primary" />
	<input type="button" value="<?=LG_BTN_RESET?>" class="btn btn-small btn-warning" id="reset" />
	<a class="excel" href="<?=url('warning/report')?>" id="excel" style="float:right;margin-right:5px;"></a>
</form>
<div id="warning"></div>
<script type="text/javascript">
$(document).ready(function(){
	$("#warning").loading("<?=url('warning/tbl')?>");
	$("#form1").submit(function(){
		$("#warning").loading("<?=url('warning/tbl')?>", $("#form1").serializeArray());
		return false;
	});
	$("#warning").on("click", "a.p", function(){
		$("#warning").loading(this.href, $("#form1").serializeArray());
		return false;
	});
	$("#excel").click(function(){
		$.posting(this.href, $("#form1").serializeArray(), function(result){
			ajaxHandler(result);
			return false;
		});
		return false;
	});
	$("#reset").click(function(){
		$.posting("<?=url('warning/reset')?>", $("#form1").serializeArray(), function(result){
			if (result == '1') {
				$.alertSuccess("Reset Risk Alert Success!");
				$("#warning").loading("<?=url('warning/tbl')?>", $("#form1").serializeArray());
			} else {
				$.alertError("Reset Risk Alert Failed!");
			}
			return false;
		});
		return false;
	});
});
</script>
<?php include tpl('footer');?>