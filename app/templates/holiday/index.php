<?php
$gTitle = LD_HOLIDAY;
$gToolbox = '<a href="'.url('holiday/import').'" class="add">'.LD_HOLIDAY_IMPORT.'</a>';
include tpl('header');
Load::js('bootstrap-datetimepicker');
?>
<form id="holidayForm" class="form-inline">
	<select name="vendorId" class="selectpicker">
		<option value="0">All Service Vendor</option>
		<?php foreach ($vendors as $vendor) {?>
		<option value="<?=$vendor['id']?>"><?=$vendor['countryShortName']?></option>
		<?php }?>	
	</select>
	<input type="text" name="year" id="year" placeholder="year" />
	<input type="submit" id="search" class="btn btn-primary btn-small" value="<?=LG_BTN_SEARCH?>" />
	<input type="button" id="clear" class="btn btn-warning btn-small" value="<?=LG_BTN_DEL?>" />
</form>
<div id="holidayList"></div>
<script type="text/javascript">
$(document).ready(function(){ 
	$("#holidayList").loading("<?=url('holiday/tbl/'.$_SESSION[USER]['page'])?>");

	$("#holidayForm").submit(function(){
		$("#holidayList").loading("<?=url('holiday/tbl')?>", $(this).serializeArray());
		return false;
	});
	$("#year").datetimepicker({
		startView: 4,
		minView: 4,
        maxView: 4,
        format: 'yyyy',
        todayBtn: 1,
		autoclose: 1,
		todayHighlight: 1
	});
	$("#clear").click(function(result){
		if (window.confirm("<?=LG_DELETE_CONFIRM?>")) {
			$.posting("<?=url('holiday/del')?>", $("#holidayForm").serializeArray(), function(result){
				ajaxHandler(result);
				return false;
			});
		}
		return false;
	});
});
</script>
<?php include tpl('footer');?>