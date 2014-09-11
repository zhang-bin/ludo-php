<?php 
$gTitle = LG_EXCHANGE_RATE;
include tpl('header');
?>
<div class="row">
	<div class="span2"><?=LG_CR?></div>
	<div class="span2"><?=$exchangeRate?><span class="hint">(USD-&gt;RMB)</span></div>
	<div><a href="<?=url('partsPrice/changeExchangeRate')?>" class="btn btn-primary"><?=LG_BTN_EDIT?></a></div>
</div>
<?php include tpl('footer');?>