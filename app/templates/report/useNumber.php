<?php
$gTitle = LG_BY_VENDOR;
$gToolbox = '<a href="'.url('report/useNumberChart').'"><i class="icon-bar-chart"></i> '.LG_USE_NUMBER_CHART.'(by vendor)</a>';
include tpl('header');
Load::js('bootstrap-datetimepicker');
?>
<form id="form1" class="form-inline">
	<select name="vendorId" class="selectpicker" data-live-search="true">
		<option value="0">All Service Vendor</option>
		<?php foreach ($vendors as $vendor) {?>
		<option value="<?=$vendor['id']?>"><?=$vendor['countryShortName']?></option>
		<?php }?>
	</select>
	<select name="partsCategory" class="selectpicker" data-live-search="true">
		<option value="0">All Parts Category</option>
		<?php foreach ($partsCategories as $partsCategory) {?>
		<option value="<?=$partsCategory['id']?>"><?=$partsCategory['partsGroupName']?></option>
		<?php }?>
	</select>
	<input type="text" name="month" id="month" placeholder="Month" />
	<input type="text" value="" name="pn" id="pn" placeholder="PN" />
	<input type="submit" value="<?=LG_BTN_SEARCH?>" class="btn btn-primary btn-small" />
	<input type="button" value="<?=LG_BTN_RESET?>" class="btn btn-small btn-warning" id="reset" />	
	<a class="excel" href="<?=url('report/useNumberReport')?>" id="excel" style="float:right;margin-right:5px;"></a>
</form>
<div id="useNumber"></div>
<script type="text/javascript">
$(document).ready(function(){
	$("#useNumber").loading("<?=url('report/useNumberList')?>");
	$("a.p").live("click", function(){
		$("#useNumber").loading(this.href, $("#form1").serializeArray());
		return false;
	});
	$("#form1").submit(function(){
		$("#useNumber").loading("<?=url('report/useNumberList')?>", $("#form1").serializeArray());
		return false;
	});
	$("#excel").click(function(){
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
		$.posting("<?=url('report/resetUseNumber')?>", $("#form1").serializeArray(), function(result){
			if (result == '1') {
				$.alertSuccess("Reset Monthly Usage Success!");
				$("#useNumber").loading("<?=url('report/useNumberList')?>", $("#form1").serializeArray());
			} else {
				$.alertError("Reset Monthly Usage Failed!");
			}
			return false;
		});
		return false;
	});
});
</script>
<?php include tpl('footer');?>