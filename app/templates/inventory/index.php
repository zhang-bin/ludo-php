<?php 
$gTitle = 'Inventory';
include tpl('header');
Load::js('autocomplete');
?>
<form id="form1" method="post" class="form-inline">
    Service Vendor:
	<select name="vendor[]" id="vendor"  multiple="multiple" size="5" class="selectpicker" data-live-search="true">
		<?php foreach ($vendors as $vendor) {?>
		<option value="<?=$vendor['id']?>"><?=$vendor['name']?></option>
		<?php }?>
	</select>
	<select name="warehouseType" id="warehouseType" class="selectpicker">
		<option value="-1">All Warehouse Type</option>
        <?php foreach (Warehouse::$_types as $typeId => $type) {?>
            <option value="<?=$typeId?>"><?=$type?></option>
        <?php }?>
	</select>
	<select name="warehouseId" id="warehouseId" class="selectpicker" data-live-search="true">
		<option value="0">All Warehouse</option>
		<?php foreach ($warehouses as $w) {?>
		<option value="<?=$w['id']?>" <?=($w['id'] == $warehouseId) ? 'selected' : ''?>><?=$w['name']?></option>
		<?php }?>
	</select>
	<input type="text" name="pn" id="pn" value="<?=$pn?>" placeholder="PN" />
	<input type="submit" value="<?=LG_BTN_SEARCH?>" class="btn btn-small btn-primary" />
    <a class="excel" href="<?=url('inventory/inventoryExport')?>" id="excel" style="float:right;margin-right:5px;"></a>
</form>
<div id="inventoryList"></div>
<script type="text/javascript">
$(document).ready(function(){
	$("#inventoryList").loading("<?=url('inventory/inventoryList')?>");
	$("#form1").submit(function(){
		$("#inventoryList").loading("<?=url('inventory/inventoryList')?>", $("#form1").serializeArray());
		return false;
	});
	$("#pn").autocomplete({
        serviceUrl: "<?=url('inventory/suggestParts')?>"
    });

	$("#vendor,#warehouseType").change(function(){
		$.getJSON("<?=url('api/getWarehouseByVendorsAndType')?>", {"vendorId": $("#vendor").val(), "type": $("#warehouseType").val()}, function(result){
			$("#warehouseId").empty();
			if (result != 0) {
				$("#warehouseId").append("<option value='0'>All Warehouse</option>");
				$(result).each(function(){
					$("#warehouseId").append("<option value='"+this.id+"'>"+this.name+"</option>");
				});
			}
            $("#warehouseId").selectpicker("refresh");
		});
	});
	$("a.p").live("click", function(){
		$("#inventoryList").loading(this.href, $("#form1").serializeArray());
		return false;
	});
	$("#excel").click(function(){
		$.posting(this.href, $("#form1").serializeArray(), function(result){
			ajaxHandler(result);
			return false;
		});
		return false;
	});

});
</script>
<?php include tpl('footer');?>