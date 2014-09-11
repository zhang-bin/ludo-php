<?php
$gTitle = LG_PARTS_DELIVERY_WAREHOUSE;
$gToolbox = '<a href="'.url('partsDelivery/warehouseAdd').'" class="add">'.LG_PARTS_DELIVERY_WAREHOUSE_ADD.'</a>';
include tpl('header');
$conf = Load::conf('PartsDelivery');
$conf = $conf['warehouse'];
?>
<form id="form1" class="form-inline">
	<input type="text" name="code" id="code" placeholder="<?=LG_PARTS_DELIVERY_CODE?>" />
	<select name="status" class="selectpicker">
		<option value="0">All Status</option>
		<?php foreach ($conf['status']  as $k => $v) {?>
		<option value="<?=$k?>"><?=$v?></opton>
		<?php }?>
	</select>
	<input type="submit" id="search" class="btn btn-primary btn-small" value="<?=LG_BTN_SEARCH?>"></input>
	<a class="excel" href="<?=url('partsDelivery/warehouseReport')?>" id="excel" style="float:right;margin-right:5px;"></a>
</form>
<div id="orderList"></div>
<script type="text/javascript">
$(document).ready(function(){
	$("#orderList").loading("<?=url('partsDelivery/warehouseTbl')?>");
	$("#form1").submit(function(){
		$("#orderList").loading("<?=url('partsDelivery/warehouseTbl')?>", $(this).serializeArray());
		return false;
	});
	
	$("a.p").live("click", function(){
		$("#orderList").loading(this.href, $("#form1").serializeArray());
		return false;
	});

	$("a[name=del]").live("click", function(){
		$(".modal").modal();
		$("#confirmDel").attr("href", this.href);
	 	return false;
	});
	$("#confirmDel").click(function(){	       
		$.post($(this).attr("href"), {}, function(result) {
          	if(ajaxHandler(result)) return;
          	return false;
		});
		return false;
	});
});
</script>
<?php include tpl('footer');?>