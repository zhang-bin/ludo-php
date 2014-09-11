<?php
$gTitle = LG_FAILURE_RATE;
$gToolbox = '<a href="'.url('failureRate/chartByShipment').'"><i class="icon-bar-chart"></i> '.LG_FAILURE_RATE_CHART.'</a>';
include tpl('header');
Load::js('bootstrap-datetimepicker');
?>
<form id="form1" class="form-inline">
	<select name="type" id="type" class="selectpicker span3">
		<option value="0">Please Choose Failure Rate</option>
		<option value="1"><?=LG_FAILURE_RATE_BY_PN?></option>
		<option value="2"><?=LG_FAILURE_RATE_BY_PN_REPLACEMENT?></option>
		<option value="3"><?=LG_FAILURE_RATE_BY_MODEL?></option>
		<option value="4"><?=LG_FAILURE_RATE_BY_SHIPMENT?></option>
	</select>
	<input type="text" name="month" id="month" placeholder="<?=LG_FAILURE_RATE_MONTH?>" />
	<select name="country" class="selectpicker" data-live-search="true">
		<option value="0">All Country</option>
		<?php foreach ($countries as $country) {?>
		<option value="<?=$country['country']?>"><?=$country['country']?></option>
		<?php }?>
	</select>
	<input type="submit" value="<?=LG_BTN_SEARCH?>" class="btn btn-small btn-primary" />
	<input type="button" value="<?=LG_BTN_RESET?>" class="btn btn-small btn-warning" id="reset" />
	<a class="excel" href="<?=url('failureRate/failureRateReport')?>" id="excel" style="float:right;margin-right:5px;"></a>
</form>
<div id="failureRate"></div>
<script type="text/javascript">
$(document).ready(function(){
	$("#form1").submit(function(){
		if ($("#type").val() == "0") {
			alert("<?=LG_FAILURE_RATE_MFFR_NOT_SELECT?>");
			return false;
		}
		if ($("#type").val() != "4" && $("#month").val() == "") {
			alert("<?=LG_FAILURE_RATE_MONTH_NOT_INPUT?>");
			return false;
		}
		$("#failureRate").loading("<?=url('failureRate/failureRateList')?>", $("#form1").serializeArray());
		return false;
	});
	$("#excel").click(function(){
		if ($("#type").val() == "0") {
			alert("<?=LG_FAILURE_RATE_MFFR_NOT_SELECT?>");
			return false;
		}
		if ($("#type").val() != "4" && $("#month").val() == "") {
			alert("<?=LG_FAILURE_RATE_MONTH_NOT_INPUT?>");
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
		if ($("#type").val() == "0") {
			alert("<?=LG_FAILURE_RATE_MFFR_NOT_SELECT?>");
			return false;
		}
		if ($("#month").val() == "") {
			alert("<?=LG_FAILURE_RATE_MONTH_NOT_INPUT?>");
			return false;
		}
		$.posting("<?=url('failureRate/resetFailureRate')?>", $("#form1").serializeArray(), function(result){
			if (result == '1') {
				$.alertSuccess("Reset Failure Rate Success!");
				$("#failureRate").loading("<?=url('failureRate/failureRateList')?>", $("#form1").serializeArray());
			} else {
				$.alertError("Reset Failure Rate Failed!");
			}
			return false;
		});
		return false;
	});
	$("#failureRate a.p").live("click", function(){
		$("#failureRate").loading(this.href, $("#form1").serializeArray());
		return false;
	});
		
});
</script>
<?php include tpl('footer');?>