<?php
$gToolbox = '<a href="'.url('partsDelivery/factoryAdd').'" class="add">'.LG_PARTS_DELIVERY_FACTORY_ADD.'</a>';
$gTitle = LG_PARTS_DELIVERY_FACTORY;
include tpl('header');
$conf = Load::conf('PartsDelivery');
$conf = $conf['factory'];
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
	<a class="excel" href="<?=url('partsDelivery/factoryReport')?>" id="excel" style="float:right;margin-right:5px;"></a>
</form>
<div id="orderList"></div>
<script type="text/javascript">
$(document).ready(function(){
	$("#orderList").loading("<?=url('partsDelivery/factoryTbl')?>", $("#form1").serializeArray());
	$("#form1").submit(function(){
		$("#orderList").loading("<?=url('partsDelivery/factoryTbl')?>", $(this).serializeArray());
		return false;
	});
	$("a.p").live("click", function(){
		$("#orderList").loading(this.href, $("#form1").serializeArray());
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