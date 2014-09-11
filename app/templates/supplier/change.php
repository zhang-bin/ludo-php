<?php
$change = isset($_GET['id']) ? true : false;
$gTitle = $change ? LG_SUPPLIER_CHANGE : LG_SUPPLIER_ADD;
include tpl('header');
?>
<form method="post" class="form form-horizontal" action="<?=$change ? url('supplier/change') : url('supplier/add')?>">
	<div class="control-group">
		<label class="control-label" for="supplier"><strong><?=LG_SUPPLIER_NAME?></strong></label>
		<div class="controls">
			<input type="text" name="supplier" id="supplier" value="<?=$supplier['supplier']?>" class="span3" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="isDefault"><strong><?=LG_SUPPLIER_DEFAULT_SUPPLIER?></strong></label>
		<div class="controls">
			<input type="checkbox" name="isDefault" id="isDefault" class="switch" <?=$supplier['isDefault'] ? 'checked' : ''?> />
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<input type="hidden" name="id" value="<?=$supplier["id"]?>" />
			<input id="submitBtn" type="submit" value="<?=LG_BTN_SAVE?>" class="btn btn-primary">
			<a class="btn" href="javascript:history.back();"><?=LG_BTN_CANCEL?></a>
		</div>
	</div>
</form>
<?php include tpl('footer');?>