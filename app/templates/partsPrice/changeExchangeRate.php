<?php 
$gTitle = LG_EXCHANGE_RATE_CHANGE;
include tpl('header');
?>
<form class="form form-inline" method="post" action="<?=url('partsPrice/changeExchangeRate')?>">
<div class="row">
	<div class="span2"><strong><?=LG_CR?></strong></div>
	<div class="span4">
		<input type="text" value="<?=$exchangeRate?>" name="value" />
		<span class="hint">(USD-&gt;RMB)</span>
	</div>
	<input type="submit" class="btn btn-primary" value="<?=LG_BTN_SAVE?>" id="submitBtn"/>
</div>
</form>
<?php include tpl('footer');?>