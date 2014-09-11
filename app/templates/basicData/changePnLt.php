<?php
$change = isset($_GET['id']) ? true : false;
$gTitle = $change ? LG_PN_LT_CHANGE : LG_PN_LT_ADD;
include tpl('header');
?>
<form method="post" class="form form-horizontal" action="<?=url($change ? 'basicData/changePnLt/'.$lt['id'] : 'basicData/addPnLt')?>">
	<div class="control-group">
    	<label class="control-label" for="pn"><strong><?=LG_PURCHASE_PN?></strong></label>
    	<div class="controls">
    		<input type="text" name="pn" id="pn" value="<?=$lt['pn']?>" />
    	</div>
    </div>
	<div class="control-group">
    	<label class="control-label" for="leadTime"><strong><?=LG_LT_LEAD_TIME?></strong></label>
    	<div class="controls">
    		<div class="input-append">
    			<input type="text" name="leadTime" id="leadTime" value="<?=$lt['leadTime']?>" />
    			<span class="add-on">Day</span>
    		</div>
    	</div>
    </div>
    <div class="control-group">
    	<div class="controls">
			<input type="hidden" id="id" name="id" value="<?=$lt['id']?>"/>
			<input id="submitBtn" type="submit" value="<?=LG_BTN_SAVE?>" class="btn btn-primary">
			<a class="btn" href="javascript:history.back();"><?=LG_BTN_CANCEL?></a>
		</div>
	</div>
</form>
<?php include tpl('footer');?>