<?php
$change = isset($_GET['id']) ? true : false;
$gTitle = $change ? LG_SC_POINT_CHANGE : LG_SC_POINT_ADD;
include tpl('header');
?>
<form method="post" class="form form-horizontal" action="<?=url($change ? 'scPoint/change/'.$point['id'] : 'scPoint/add')?>">
	<div class="control-group">
		<label class="control-label" for="point"><strong><?=LG_SC_POINT_NAME?></strong></label>
		<div class="controls">
			<input name="point" id="point" value="<?=$point['point']?>" />
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<input type="hidden" id="id" name="id" value="<?=$point['id']?>"/>
			<input id="submitBtn" type="submit" value="<?=LG_BTN_SAVE?>" class="btn btn-primary">
			<a class="btn" href="javascript:history.back();"><?=LG_BTN_CANCEL?></a>
		</div>
	</div>
</form>
<?php include tpl('footer');?> 