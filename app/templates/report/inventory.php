<?php
$gTitle = LG_INVENTORY_LIST;
$gToolbox = '<a href="'.url('report/inventoryChart').'"><i class="icon-bar-chart"></i> '.LG_INVENTORY_CHART.'</a>';
include tpl('header');
Load::js('bootstrap-datetimepicker');
?>
<form id="form1" class="form-inline">
	<select name="vendorId" class="selectpicker" data-live-search="true">
		<option value="0">All Service Vendor</option>
		<?php foreach ($vendors as $vendor) {if (empty($vendor['countryShortName'])) continue;?>
		<option value="<?=$vendor['id']?>"><?=$vendor['countryShortName']?></option>
		<?php }?>
	</select>
    <select name="partsCategory" class="selectpicker" data-live-search="true">
        <option value="0">All Parts Category</option>
        <?php foreach ($partsCategories as $partsCategory) {?>
            <option value="<?=$partsCategory['id']?>"><?=$partsCategory['partsGroupName']?></option>
        <?php }?>
    </select>
    <select name="goodOrBad" class="selectpicker" id="goodOrBad">
        <option value="-1">All Warehouse Type</option>
        <?php foreach (Warehouse::$_types as $typeId => $type) {?>
            <option value="<?=$typeId?>"><?=$type?></option>
        <?php }?>
    </select>
    <input type="text" name="month" id="month" placeholder="Month" />
	<input type="text" value="" name="pn" id="pn" placeholder="PN" />
	<input type="submit" value="<?=LG_BTN_SEARCH?>" class="btn btn-primary btn-small" />
	<input type="button" value="<?=LG_BTN_RESET?>" class="btn btn-small btn-warning" id="reset" />
	<a class="excel" href="<?=url('report/inventoryReport')?>" id="excel" style="float:right;margin-right:5px;"></a>
</form>
<div id="inventory"></div>
<script type="text/javascript">
$(document).ready(function(){
	$("#inventory").loading("<?=url('report/inventoryList')?>");
	$("a.p").live("click", function(){
		$("#inventory").loading(this.href, $("#form1").serializeArray());
		return false;
	});
	$("#form1").submit(function(){
		$("#inventory").loading("<?=url('report/inventoryList')?>", $("#form1").serializeArray());
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
		$.posting("<?=url('report/resetInventory')?>", $("#form1").serializeArray(), function(result){
			if (result == '1') {
				$.alertSuccess("Reset Parts Inventory Success!");
				$("#inventory").loading("<?=url('report/inventoryList')?>", $("#form1").serializeArray());
			} else {
				$.alertError("Reset Parts Inventory Failed!");
			}
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
});
</script>
<?php include tpl('footer');?>