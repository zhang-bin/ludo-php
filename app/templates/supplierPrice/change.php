<?php
$change = isset($_GET['id']) ? true : false;
$gTitle = $change ? LG_PARTSPRICE_MODIFY : LG_PARTSPRICE_ADD; 
include tpl('header');
?>
<form method="post" class="form form-horizontal" action="<?=url('partsPrice/modifySupplierPrice')?>">
	<fieldset>
	<div class="control-group">
    	<label class="control-label">PartsPN</label>
    	<div class="controls"><span style="line-height:30px;"><?=$partsprice["pn"] ?></span></div>
  	</div>
	<div class="control-group">
    	<label class="control-label" for="rmb"><?=LG_PARTSPRICE_RMB?></label>
    	<div class="controls"><input type="text"  name="rmb" id="rmb" value="<?=Crypter::decrypt($partsprice['rmb'])?>" /></div>
  	</div>
	<div class="control-group">
    	<label class="control-label" for="usd"><?=LG_PARTSPRICE_USD?></label>
    	<div class="controls"><input type="text"  name="usd" id="usd" value="<?=Crypter::decrypt($partsprice['usd'])?>" /></div>
  	</div>
  	<div class="control-group">
    	<div class="controls">
			<input type="hidden" id="id" name="id" value="<?=$partsprice['id']?>" />
  			<input type="submit" id="submitBtn" class="btn btn-primary" value="<?=LG_BTN_SAVE?>" />
			<a class="btn" href="javascript:history.back();"><?=LG_BTN_CANCEL?></a>
		</div>
	</div>
	</fieldset>
</form>
<?php include tpl('footer')?>