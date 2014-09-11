<?php
$gTitle = LG_KPI;
$gToolbox = '<a href="'.url('kpi/chartIndex').'"><i class="icon-bar-chart"></i> '.LG_KPI_CHART.'</a>';
include tpl('header');
$thisYear = date('Y');
?>
<form id="form1" class="form-inline">
	<select name="vendorId" id="vendorId" class="selectpicker" data-live-search="true">
		<option value="0">All Service Vendor</option>
		<?php foreach ($vendors as $vendor) {?>
		<option value="<?=$vendor['id']?>"><?=$vendor['name']?></option>
		<?php }?>
	</select>
	<select name="year" id="year" class="selectpicker">
		<option value="0">Please Choose Year</option>
		<?php for ($i = 2012; $i <= $thisYear; $i++) {?>
		<option value="<?=$i?>"><?=$i?></option>
		<?php }?>
	</select>
	<input type="submit" id="search" class="btn btn-primary btn-small" value="<?=LG_BTN_SEARCH?>"></input>
	<a class="excel" href="<?=url('kpi/export')?>" id="excel" style="float:right;margin-right:5px;"></a>
</form>
<div id="kpi"></div>
<script type="text/javascript">
$(document).ready(function(){
	$("#form1").submit(function(){
		if ($("#vendorId").val() == "0") {
			alert("<?=LG_KPI_VENDOR_EMPTY?>");
			return false;
		}
		if ($("#year").val() == "0") {
			alert("<?=LG_KPI_YEAR_EMPTY?>");
			return false;
		}
			
		$("#kpi").loading("<?=url('kpi/tbl')?>", $(this).serializeArray());
		return false;
	});

	$("#excel").click(function(){
		if ($("#vendorId").val() == "0") {
			alert("<?=LG_KPI_VENDOR_EMPTY?>");
			return false;
		}
		if ($("#year").val() == "0") {
			alert("<?=LG_KPI_YEAR_EMPTY?>");
			return false;
		}
		$.post(this.href, $("#form1").serializeArray(), function(result){
			ajaxHandler(result);
			return false;
		});
		return false;
	});
});
</script>
<?php include tpl('footer');?>