<?php
$gTitle = LG_ABC_CLASS;
include tpl('header');
Load::js('bootstrap-datetimepicker');
?>
<form id="form1" class="form-inline">
	<select name="vendor" class="selectpicker" data-live-search="true">
		<option value="0">All Service Vendor</option>
		<?php foreach ($vendors as $vendor) {?>
		<option value="<?=$vendor['id']?>"><?=$vendor['countryShortName']?></option>
		<?php }?>
	</select>
	<input type="text" name="month" id="month" placeholder="<?=LG_ABC_CLASS_MONTH?>" />
	<input type="text" name="pn" id="pn" placeholder="<?=LG_ABC_CLASS_PN?>" />
	<input type="submit" value="<?=LG_BTN_SEARCH?>" class="btn btn-primary btn-small" />
	<input type="button" value="<?=LG_BTN_RESET?>" class="btn btn-small btn-warning" id="reset" />
	<a class="excel" href="<?=url('abcClass/report')?>" id="excel" style="float:right;margin-right:5px;"></a>
</form>
<div id="abcClass"></div>
<script type="text/javascript">
$(document).ready(function(){
	$("#form1").submit(function(){
		if ($("#month").val() == "") {
			alert("<?=LG_ABC_CLASS_MONTH_NOT_INPUT?>");
			return false;
		}
		$("#abcClass").loading("<?=url('abcClass/tbl')?>", $("#form1").serializeArray());
		return false;
	});
	$("#abcClass").on("click", "a.p", function(){
		$("#abcClass").loading(this.href, $("#form1").serializeArray());
		return false;
	});
	$("#excel").click(function(){
		if ($("#month").val() == "") {
			alert("<?=LG_ABC_CLASS_MONTH_NOT_INPUT?>");
			return false;
		}
		$.posting(this.href, $("#form1").serializeArray(), function(result){
			ajaxHandler(result);
			return false;
		});
		return false;
	});
	$("#month").datetimepicker({
		startView: 3,
		minView: 3,
        maxView: 3,
        format: 'yyyy-mm',
        todayBtn: 1,
		autoclose: 1,
		todayHighlight: 1
	});
	$("#reset").click(function(){
		$.posting("<?=url('abcClass/reset')?>", $("#form1").serializeArray(), function(result){
			if (result == '1') {
				$.alertSuccess("Reset Parts ABC Classify Success!");
				$("#abcClass").loading("<?=url('abcClass/tbl')?>", $("#form1").serializeArray());
			} else {
				$.alertError("Reset Parts ABC Classify Failed!");
			}
			return false;
		});
		return false;
	});
});
</script>
<?php include tpl('footer');?>