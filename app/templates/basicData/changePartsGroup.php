<?php
$change = isset($_GET['id']) ? true : false;
$gTitle = $change ? LG_PARTS_GROUP_CHANGE : LG_PARTS_GROUP_ADD;
include tpl('header');
?>
<form method="post" class="form-horizontal form" action="<?=url($change ? 'basicData/changePartsGroup/'.$partsGroup['id'] : 'basicData/addPartsGroup')?>">
	<fieldset>
	<div class="control-group">
    	<label class="control-label" for="partsGroupName">
    		<strong>Parts Category</strong>
    	</label>
    	<div class="controls">
      		<input type="text" class="span3" name="partsGroupName" id="partsGroupName" value="<?=$partsGroup['partsGroupName']?>" />
    	</div>
  	</div>
  	<div class="control-group">
    	<div class="controls">
			<input type="hidden" id="id" name="id" value="<?=$partsGroup['id']?>" />
  			<input type="submit" id="submitBtn" class="btn btn-primary" value="<?=LG_BTN_SAVE?>" />
			<a class="btn" href="javascript:history.back();"><?=LG_BTN_CANCEL?></a>
		</div>
	</div>
	</fieldset>
</form>
<?php include tpl('footer');?>