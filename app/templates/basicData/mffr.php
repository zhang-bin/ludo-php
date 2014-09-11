<?php 
$gTitle = LG_SETTING_MFFR;
include tpl('header');
Load::js('bootstrap-datetimepicker');
Load::js('bootstrap-editable');
?>
<form id="searchMffr" class="form-inline">
	<select name="country" data-live-search="true" class="selectpicker">
	  	<option value="0">All Country</option>
	 	<?php foreach ($countries as $country) {?>
	 	<option value="<?=$country['country']?>"><?=$country['country']?></option>
	  	<?php }?>
	</select>
	<select name="model" data-live-search="true" class="selectpicker">
	  	<option value="0">All Model</option>
	 	<?php foreach ($models as $model) {?>
	 	<option value="<?=$model?>"><?=$model?></option>
	  	<?php }?>
	</select>
	<select name="category" data-live-search="true" class="selectpicker">
	  	<option value="0">All Parts Category</option>
	 	<?php foreach ($categories as $category) {?>
	 	<option value="<?=$category['id']?>"><?=$category['partsGroupName']?></option>
	  	<?php }?>
	</select>
	<input type="text" name="month" id="month" value="" placeholder="<?=LG_FAILURE_RATE_MONTH?>" />
	
	<input type="submit" class="btn btn-primary btn-small" value="<?=LG_BTN_SEARCH?>" />
	<input type="button" class="btn btn-warning btn-small" value="Reset" id="sync" />
	<a class="btn btn-success btn-small" href="<?=url('basicData/uploadMffr')?>">Upload</a>
</form>
<div id="mffr"></div>
<script type="text/javascript">
$(document).ready(function(){
	$("#mffr").loading("<?=url('basicData/mffrTbl')?>");
	$("#mffr").on("click", "a.p", function(){
		$("#mffr").loading(this.href, $("#searchMffr").serializeArray());
		return false;
	});
	$("#searchMffr").submit(function(){
		$("#mffr").loading("<?=url('basicData/mffrTbl')?>", $("#searchMffr").serializeArray());
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
	$("#sync").click(function(){
		var month = $("#month").val();
		if (month == '') {
			alert("please input month");
			return false;
		}
		$.posting("<?=url('basicData/syncMffr')?>", {"month": $("#month").val()}, function(result){
			if (result == '0') {
				$.alertError("Sync Failure Rate Failed!");
			} else {
				$.alertSuccess("Sync Failure Rate Success!");
				$("#mffr").loading("<?=url('basicData/mffrTbl')?>", $("#searchMffr").serializeArray());
			}
			return false;
		});
		return false;
	});
});
</script>
<?php include tpl('footer');?>