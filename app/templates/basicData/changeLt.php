<?php
$conf = Load::conf('setting');
$change = isset($_GET['id']) ? true : false;
$title = $change ? LG_LT_CHANGE : LG_LT_ADD;
?>
<form method="post" class="form" id="ltForm" action="<?=url($change ? 'basicData/changeLt/'.$lt['id'] : 'basicData/addLt')?>">
<table class="formtable" width="100%">
	<tr>
		<th colspan="3"><?=$title?></th>
	</tr>
	<tr>
		<td style="text-align:right;"><span class="red">*</span><?=LG_LT_SUPPLIER?>： </td>
	  	<td align="left"><select name="supplier">
	  		<option value="0"><?=LG_SELECT_CHOOSE?></option>
	  		<?php foreach ($suppliers as $supplier) {?>
	  		<option value="<?=$supplier['id']?>" <?=($supplier['id'] == $lt['supplierId']) ? 'selected' : ''?>><?=$supplier['supplier']?></option>
	  		<?php }?>
	  	</select></td>
	</tr>
	<tr>
		<td style="text-align:right;"><?=LG_LT_PO_TYPE?>： </td>
	  	<td align="left"><select name="poType">
	  		<option value="0"><?=LG_SELECT_CHOOSE?></option>
	  		<?php foreach ($conf['poType'] as $k => $v) {?>
	  		<option value="<?=$k?>" <?=($k == $lt['poType']) ? 'selected' : ''?>><?=$v?></option>
	  		<?php }?>
	  	</select></td>
	</tr>
	<tr>
		<td style="text-align:right;"><?=LG_LT_LEAD_TIME?>： </td>
	  	<td align="left"><input type="text" name="leadTime" value="<?=$lt['leadTime']?>" />(Day)</td>
	</tr>
	<tr>
		<td class="tablebottom">&nbsp;</td>
		<td class="tablebottom">
			<input type="hidden" id="id" name="id" value="<?=$lt['id']?>"/>
			<input id="submitBtn" type="submit" value="<?=LG_BTN_SAVE?>" class="btn btn-primary">
			<a class="btn jump" href="<?=url('basicData/lt')?>"><?=LG_BTN_CANCEL?></a>
		</td>
	</tr>
</table>
</form>