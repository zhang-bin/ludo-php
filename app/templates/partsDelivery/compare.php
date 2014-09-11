<?php
$gTitle = LG_PARTS_DELIVERY_OPER;
include tpl('header');
?>
<form method="post" class="form-horizontal" action="<?=url('partsDelivery/compare')?>" enctype="multipart/form-data">
	<div class="control-group">
		<label class="control-label"><strong><?=LG_PO_FILE?></strong></label>
		<div class="controls">
			<input type="file" name="parts" id="parts" value="" />
			<a href="<?=rurl('static/pickup-list.xlsx')?>" target="_blank">Sample File</a>
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<input id="submitBtn" type="submit" value="<?=LG_BTN_SAVE?>" class="btn btn-primary" />
			<input id="cancelBtn" type="button"  value="<?=LG_BTN_CANCEL?>" class="btn" onclick="javascript:history.back();" />
		</div>
	</div>
</form>
<?php include tpl('footer');?>