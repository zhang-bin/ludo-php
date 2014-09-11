<?php
$change = isset($_GET['id']) ? true : false;
$gTitle = $change ? LG_SC_ROUTE_CHANGE : LG_SC_ROUTE_ADD;
include tpl('header');
$conf = Load::conf('Setting');
?>
<form class="form form-horizontal" method="post" action="<?=url($change ? 'scRoute/change/'.$route['id'] : 'scRoute/add')?>">
	<div class="control-group">
		<label class="control-label" for="name"><strong><?=LG_SC_ROUTE_NAME?></strong></label>
		<div class="controls">
			<input name="name" id="name" class="span3" value="<?=$route['name']?>" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="vendor"><strong><?=LG_SC_ROUTE_COUNTRY?></strong></label>
		<div class="controls">
			<select name="country" id="country" class="selectpicker" data-live-search="true">
		  		<option value="0"><?=LG_SELECT_CHOOSE?></option>
		  		<?php foreach ($countries as $country) {?>
		  		<option value="<?=$country['country']?>" <?=($country['country'] == $route['country']) ? 'selected' : ''?>><?=$country['country']?></option>
		  		<?php }?>
		  	</select>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="poType"><strong><?=LG_SC_ROUTE_LT_PO?></strong></label>
		<div class="controls">
			<select name="poType" id="poType" class="selectpicker">
		  		<option value="0"><?=LG_SELECT_CHOOSE?></option>
		  		<?php foreach ($conf['poType'] as $k => $v) {?>
		  		<option value="<?=$k?>" <?=($k == $route['poType']) ? 'selected' : ''?>><?=$v?></option>
		  		<?php }?>
		  	</select>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="tat"><strong><?=LG_SC_ROUTE_TAT?></strong></label>
		<div class="controls" id="tat">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="lt"><strong><?=LG_SC_ROUTE_LT?></strong></label>
		<div class="controls" id="lt">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="remark"><strong><?=LG_SC_ROUTE_REMARK?></strong></label>
		<div class="controls">
			<textarea rows="4" class="span4" name="remark" id="remark"><?=$route['remark']?></textarea>
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<input type="hidden" id="id" name="id" value="<?=$route['id']?>"/>
			<input id="submitBtn" type="submit" value="<?=LG_BTN_SAVE?>" class="btn btn-primary">
			<a class="btn" href="javascript:history.back();"><?=LG_BTN_CANCEL?></a>
		</div>
	</div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	$("#poType").change(function(){
		if ($(this).val() == '0') return;
		$("#lt").loading("<?=url('scRoute/getLT')?>", {"type": $(this).val(), "id": "<?=$route['id']?>"});
		return false;
	});
	$("#poType").change();
	$("#tat").loading("<?=url('scRoute/getTAT')?>", {"id": "<?=$route['id']?>"});
});
</script>
<?php include tpl('footer');?> 