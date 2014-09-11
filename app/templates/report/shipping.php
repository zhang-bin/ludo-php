<?php
$gTitle = LG_SHIPPING_LIST;
include tpl('header');
?>
<form id="form1" class="form-inline">
	<select name="vendorId" class="selectpicker" data-live-search="true">
		<option value="0">All Destination Depot</option>
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
	<input type="text" value="" name="pn" id="pn" placeholder="PN" />
	<input type="submit" value="<?=LG_BTN_SEARCH?>" class="btn btn-primary btn-small" />
	<input type="button" value="<?=LG_BTN_RESET?>" class="btn btn-small btn-warning" id="reset" />
	<a class="excel" href="<?=url('report/shippingReport')?>" id="excel" style="float:right;margin-right:5px;"></a>
</form>
<div id="shipping"></div>
<script type="text/javascript">
$(document).ready(function(){
	$("#shipping").loading("<?=url('report/shippingList')?>");
	$("a.p").live("click", function(){
		$("#shipping").loading(this.href, $("#form1").serializeArray());
		return false;
	});
	$("#form1").submit(function(){
		$("#shipping").loading("<?=url('report/shippingList')?>", $("#form1").serializeArray());
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
		$.posting("<?=url('report/resetShipping')?>", $("#form1").serializeArray(), function(result){
			if (result == '1') {
				$.alertSuccess("Reset Shipping On Way Success!");
				$("#shipping").loading("<?=url('report/shippingList')?>", $("#form1").serializeArray());
			} else {
				$.alertError("Reset Shipping On Way Failed!");
			}
			return false;
		});
		return false;
	});
});
</script>
<?php include tpl('footer');?>