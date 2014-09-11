<?php 
$gTitle = LG_SETTING_TAT;
$gToolbox = '<a href="'.url('basicData/addTat').'" class="add">'.LG_TAT_ADD.'</a>';
include tpl('header');
?>
<form id="searchTat" class="form-inline">
	<?=LG_TAT_FROM?>:
	<select name="from" class="selectpicker" data-live-search="true">
	  	<option value="0"><?=LG_SELECT_CHOOSE?></option>
	 	<?php foreach ($points as $point) {?>
	 	<option value="<?=$point['id']?>"><?=$point['point']?></option>
	  	<?php }?>
	</select>
	<?=LG_TAT_TO?>:
	<select name="to" class="selectpicker" data-live-search="true">
	  	<option value="0"><?=LG_SELECT_CHOOSE?></option>
	 	<?php foreach ($points as $point) {?>
	 	<option value="<?=$point['id']?>"><?=$point['point']?></option>
	  	<?php }?>
	</select>
	<input type="submit" class="btn btn-primary btn-small" value="<?=LG_BTN_SEARCH?>" />
</form>
<div id="tat" style="margin-top:10px;"></div>
<script type="text/javascript">
$(document).ready(function(){
	$("#tat").loading("<?=url('basicData/tatTbl')?>");
	$("#tat").on("click", "a.p", function(){
		$("#tat").loading(this.href, $("#searchTat").serializeArray());
		return false;
	});
	$("#searchTat").submit(function(){
		$("#tat").loading("<?=url('basicData/tatTbl')?>", $("#searchTat").serializeArray());
		return false;
	});
});
</script>
<?php include tpl('footer');?>