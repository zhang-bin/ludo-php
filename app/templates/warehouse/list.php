<?php 
$gTitle = LG_WAREHOUSE_LIST;
include tpl('header');
?>
<form method="post" id="form1" class="form-inline">
	<select name="vendor" class="selectpicker" data-live-search="true" id="vendor">
		<option value="0">All Service Vendor</option>
		<?php foreach ($vendors as $vendor) {?>
		<option value="<?=$vendor['id']?>"><?=$vendor['name']?></option>
		<?php }?>
	</select>
	<select name="station" class="selectpicker" data-live-search="true" id="station">
		<option value="0">All Station</option>
	</select>
	<select name="goodOrBad" class="selectpicker" id="goodOrBad">
		<option value="-1">All Warehouse Type</option>
        <?php foreach (Warehouse::$_types as $typeId => $type) {?>
            <option value="<?=$typeId?>"><?=$type?></option>
        <?php }?>
	</select>
	<input type="submit" class="btn btn-small btn-primary" value="<?=LG_BTN_SEARCH?>" />
</form>
<div id="whList"></div>
<script type="text/javascript">
$(document).ready(function(){
	$("#whList").loading("<?=url('warehouse/tbl')?>");
	$("#form1").submit(function(){
		$("#whList").loading("<?=url('warehouse/tbl')?>", $(this).serialize());
		return false;
	});
	$("a.p").live("click", function(){
		$("#whList").loading(this.href, $("#form1").serialize());
		return false;
	});
	$("#vendor").change(function(){
		$.getJSON("<?=url('api/getStationByVendor')?>", {"id":$(this).val()}, function(result){
			$("#station").empty();
			$("#station").append('<option value="0">All Station</option>')
			$(result).each(function(){
				$("#station").append('<option value="'+this.id+'">'+this.name+'</option>');
			});	
			$("#station").selectpicker("refresh");
			return;
		});
	});
});
</script>
<?php	 include tpl('footer');?>