<?php
$gToolbox = '<a href="'.url('purchaseOrder/add').'" class="add">'.LG_PO_ADD.'</a>';
$gTitle = LG_PO_CLAIM;
include tpl('header');
Load::js('bootstrap-datetimepicker');
$conf = Load::conf('PurchaseOrder');
?>
<form id="form1" class="form-inline">
	<?=LG_PO_CODE?>:
	<input type="text" name="code" id="code"></input>
	&emsp;
	<?=LG_PO_CREATE_TIME?>:
	<input type="text" name="from" id="from"></input>
	To:
	<input type="text" name="to" id="to"></input>
	&emsp;
	<?=LG_PO_SUPPLIER?>:
	<select name="supplier" class="selectpicker" data-live-search="true">
		<option value="0"><?=LG_SELECT_CHOOSE?></option>
		<?php foreach ($suppliers as $supplier) {?>
		<option value="<?=$supplier['id']?>"><?=$supplier['supplier']?></option>
		<?php }?>
	</select>
	<p>
	<?=LG_PO_TYPE?>:
	<select name="type[]" multiple="multiple" class="selectpicker" data-live-search="true">
		<?php foreach ($conf['type'] as $k=>$type) {?>
		<option value="<?=$k?>"><?=$type?></option>
		<?php }?>
	</select>
	<?=LG_PO_STATUS?>:
	<select name="status[]" multiple="multiple" class="selectpicker" data-live-search="true">
		<?php foreach ($conf['status'] as $k=>$status) {?>
		<option value="<?=$k?>" <?=($k == PurchaseOrder::STATUS_PROCESS) ? 'selected' : ''?>><?=$status?></option>
		<?php }?>
	</select>
	<input type="submit" id="search" class="btn btn-primary btn-small" value="<?=LG_BTN_SEARCH?>"></input>
	<a class="excel" href="<?=url('purchaseOrder/report')?>" id="excel" style="float:right;margin-right:5px;"></a>
</form>
<div id="orderList"></div>
<script type="text/javascript">
$(document).ready(function(){
	$("#from").datetimepicker({
		startView: 2,
		minView: 2,
        maxView: 2,
        format: 'yyyy-mm-dd',
		autoclose: 1,
		endDate: "<?=date(DATE_FORMAT)?>"
	}).on("changeDate", function(ev){
		$("#to").datetimepicker("setStartDate", ev.date);
	});

	$("#to").datetimepicker({
		startView: 2,
		minView: 2,
        maxView: 2,
        format: 'yyyy-mm-dd',
		autoclose: 1,
		endDate: "<?=date(DATE_FORMAT)?>"
	}).on("changeDate", function(ev){
		$("#from").datetimepicker("setEndDate", ev.date);
	});
	$("#orderList").loading("<?=url('purchaseOrder/tbl')?>", $("#form1").serializeArray());
	$("#form1").submit(function(){
		$("#orderList").loading("<?=url('purchaseOrder/tbl')?>", $(this).serializeArray());
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